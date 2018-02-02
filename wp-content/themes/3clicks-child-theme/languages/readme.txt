To translate or just change some phrases (without changing current language) you can use built-in Wordpress
translation mechanism. To keep all changes safe you should use a child theme. This instruction will walk you
through all necessary translation steps.

Do as follows:
1) copy "3clicks/languages/g1_theme.po" file to "3clicks-child-theme/languages/" folder.

2) rename the file "g1_theme.po" to "YOUR_LANGUAGE_CODE.po". You can find all available codes here:
http://codex.wordpress.org/WordPress_in_Your_Language

For example:
- if you use german, your target file will be "de_DE.po"
- for spanish it'll be "es_ES.po"

3) open this new file (in this guide I'll use de_DE.po) in some translation tool (eg. poEdit - we recommended this tool).
If you decide to use poEdit, you have to enable the option "Automaticaly compile .mo file on save" located in
"File > Preferences > Editor" section. This way poEdit will generate .mo file after each modification of .po file.

4) use poEdit (or any other tool) to translate desired phrases.

5) after each save, poEdit should generate de_DE.mo file.
Those both files are required for Wordpress. You should have them inside "3clicks-child-theme/languages" folder.

5) now it's time to tell Wordpress which translation file should be used.
Open your "WP_INSTALLATION_FOLDER/wp-config.php" file and change the line:

define('WP_LANG', '');

to:

define('WP_LANG', 'de_DE');

6) That's all. Your translations should be now visible.


Final word:
-----------

If you use english language, you don't need to define any language code.
Just leave it empty:

define('WP_LANG', '');


and name your target file "en_US.po". "en_US" is default WP_LANG.

