clean:
	rm -rf build

update:
	ppm --generate-package="src/CoffeeHouse"

build:
	mkdir build
	ppm --no-intro --compile="src/CoffeeHouse" --directory="build"

install:
	ppm --no-prompt --fix-conflict --branch="production" --install="build/net.intellivoid.coffeehouse.ppm"