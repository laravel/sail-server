curl https://laravel.build/docker/7.4 | docker build -t sail/installer -

docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    sail/installer:latest \
    composer create-project laravel/laravel {{ name }}

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
