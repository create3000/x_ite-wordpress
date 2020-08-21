=== X_ITE ===
Contributors: create3000
Donate link: http://create3000.de/x_ite/
Tags: X_ITE, X3D, X3DCanvas, embed, browser
Requires at least: 3.0
Tested up to: 5.0
Stable tag: 4.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

[X3DCanvas src="https://cdn.rawgit.com/create3000/Library/master/Examples/X_ITE/info.x3d" class="x_ite-browser"] shortcode

== Description ==

> **[Check Out X_ITE](http://create3000.de/x_ite/ "X_ITE Home Page")** |

[X3DCanvas src="https://cdn.rawgit.com/create3000/Library/master/Examples/X_ITE/info.x3d" class="x_ite-browser"] shortcode
should show a X3DCanvas element like this:

[X_ITE](http://create3000.de/x_ite/)

X_ITE is a new X3D Browser engine entirely written in JavaScript and uses WebGL for 3D rendering. Authors can publish
X3D source within an HTML5 page with X_ITE that works with Web browsers without prior plugin installation. This gives
X3D authors the ability to displays content in 3D, using WebGL 3D graphics technology to display X3D content in several
different browsers across several different operating systems.

== Other Notes ==

= X3DCanvas params: =
* **src** - source of the X3DCanvas: `[X3DCanvas src="http://www.example.com/world.x3d"]`;";
* **id** - allows to add the id of the X3DCanvas: `[X3DCanvas id="custom-id"]`; removed by default;
* **class** - allows to add the class of the X3DCanvas: `[X3DCanvas class="custom-class"]`; by default class="x_ite-browser";
* **style** - allows to add the css styles of the X3DCanvas: `[X3DCanvas style="margin-left:-30px;"]`; removed by default;
* **any_other_param** - allows to add new parameter of the X3DCanvas `[X3DCanvas any-other-param="any-value"]`;

== Screenshots ==

1. [X3DCanvas] shortcode

== Changelog ==

= 1.0.4 =

* Changed servers

= 1.0.2 =
* Added minified version option

= 1.0.1 =
* Added NURBS component

= 1.0.0 =
* Initial release

== Installation ==

1. install and activate the plugin on the Plugins page
2. add shortcode `[X3DCanvas src="https://cdn.rawgit.com/create3000/Library/master/Examples/X_ITE/info.x3d" class="x_ite-browser"]` to page or post content
