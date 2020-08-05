clean:
	rm -rf build

build:
	mkdir build
	ppm --no-intro --compile="src/DeepAnalytics" --directory="build"

install:
	ppm --no-intro --no-prompt --install="build/net.intellivoid.deepanalytics.ppm"