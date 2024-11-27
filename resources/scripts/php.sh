# Ensure that Docker is running, otherwise exit
docker info > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "Docker is not running. Exiting."
    exit 1
fi

docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php{{ php }}-composer:latest \
    bash -c "laravel new {{ name }} --no-interaction && cd {{ name }} && php ./artisan sail:install --with={{ with }} {{ devcontainer }}"

cd {{ name }}

# Allow build with no additional services
if [ "{{ services }}" == "none" ]; then
    ./vendor/bin/sail build
else
    ./vendor/bin/sail pull {{ services }}
    ./vendor/bin/sail build
fi

CYAN='\033[0;36m'
LIGHT_CYAN='\033[1;36m'
BOLD='\033[1m'
NC='\033[0m'

echo ""

# Attempt to adjust permissions without privilege elevation
if ! chown -R "$USER": . 2>/dev/null; then
    CHOWN_ERROR=true

    # Check for privilege elevation tools (doas or sudo)
    if command -v doas &>/dev/null; then
        SUDO="doas"
    elif command -v sudo &>/dev/null; then
        SUDO="sudo"
    else
        SUDO=""
    fi

    # Attempt to adjust permissions with privilege elevation
    if [ -n "$SUDO" ]; then
        echo -e "${BOLD}Attempting to adjust permissions with elevated privileges.${NC}"
        echo -e "${BOLD}Please provide your password so we can make some final adjustments to your application's permissions.${NC}"
        echo ""
        if $SUDO chown -R "$USER": . 2>/dev/null; then
            CHOWN_ERROR=false
        fi
    fi

    # If applicable, print permissions adjustment error and exit
    if [ "$CHOWN_ERROR" = true ]; then
        echo -e "${BOLD}Failed to adjust permissions.${NC}"
        echo -e "${BOLD}You can still try running the application. It might work without adjusted permissions.${NC}"
        exit 2
    fi
fi

echo -e "${BOLD}All done! We hope you build something incredible. Get started with:${NC} cd {{ name }} && ./vendor/bin/sail up"
exit 0
