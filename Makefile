clean:
	rm -rf build

build:
	mkdir build
	ppm --no-intro --compile="src/DeepAnalytics" --directory="build"

update:
	ppm --generate-package="src/DeepAnalytics"

install:
	ppm --no-intro --no-prompt --fix-conflict --branch="production" --install="build/net.intellivoid.deepanalytics.ppm"

install_fast:
	ppm --no-intro --no-prompt --fix-conflict --skip-dependencies --branch="production" --install="build/net.intellivoid.deepanalytics.ppm"