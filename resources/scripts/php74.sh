docker info > /dev/null 2>&1

# Ensure that Docker is running...
if [ $? -ne 0 ]; then
    echo "Docker is not running."

    exit 1
fi

docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php74-composer:latest \
    laravel new {{ name }}

cd {{ name }}

CYAN='\033[0;36m'
LIGHT_CYAN='\033[1;36m'
NC='\033[0m'

echo -e "${LIGHT_CYAN}"
echo "             /|~~~"
echo "           ///|"
echo "         /////|"
echo "       ///////|"
echo "     /////////|"
echo "   \==========|===/"
echo "~~~~~~~~~~~~~~~~~~~~~"
echo -e "${NC}"

if sudo -n true 2>/dev/null; then
    sudo chown -R $USER: .
    echo -e "${CYAN}We hope you build something incredible. Get started with:${NC} cd {{ name }} && ./sail up"
else
    echo -e "${CYAN}Please provide your password so we can make some final adjustments to your application's permissions.${NC}"
    echo ""
    sudo chown -R $USER: .
    echo ""
    echo -e "${CYAN}Thank you! We hope you build something incredible. Dive in with:${NC} cd {{ name }} && ./sail up"
fi
