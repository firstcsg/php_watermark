imagick下载地址：https://pecl.php.net/package/imagick
下载版本需要对应php版本;
安装：解压然后将php_imagick.dll复制到php/ext目录##你php存放拓展的目录
         修改php.ini 加上 extension=php_imagick.dll；
下载 imagemagick程序,地址：https://imagemagick.org/script/download.php#windows



ffmpeg 视频添加水印
ffmpeg -y -i test.mp4 -vf drawtext=fontfile=simhei.ttf:text="水印内容":x=w/10:y=h/2:fontsize=24:fontcolor=white:shadowy=2 newTest.mp4

ffmpeg -y -i test.mp4 drawtext=fontfile=simhei.ttf:text="kirito":x=w/10:y=h/2:fontsize=24:fontcolor=white :newTest2.mp4