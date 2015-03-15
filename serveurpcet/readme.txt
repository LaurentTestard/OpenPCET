Prerequi
Aller dans sur tuleap => Ma page personnelle => Gestion du compte => editer cle ssh  [https://forge.projetsdetudiants.net/account/editsshkeys.php]
	Suiver les indications ( sans ea pas de commit possible sur les depos )

## Dependance
Telecharger Git [http://git-scm.com/download/win]
	Installer le 

Telecharger Eclipse-PHP [http://kickass.to/eclipse-php-t8841070.html]
	Decompresser le

Telecharger Wamps [http://www.wampserver.com/]
	Installer le
	
Telecharger Composer [https://getcomposer.org/Composer-Setup.exe]
	Installer le


===
Variable d'environement
Modifier la variable d'environement sur windows (system => "path") ajouter le chemin vers le dossier contenant l'executable php 
	Exemple : [etc...];C:\wamp\bin\php\php5.4.16
Notez les noobs qu'il faut redemarer les logiciel y comprit les consoles(cmd) pour la prises en compte des variable d'environnement.
===
Installer Pear and PHPUnit (test cote serveur en mode commande)
Ouvrire le fichier php.ini (ex: C:\wamp\bin\php\php5.4.16\php.ini)
Remplacer
	;phar.require_hash = On
Par
	phar.require_hash = Off
Enregistrer php.ini

Telecharger pear [http://pear.php.net/go-pear.phar]
	Mettre le fichier e un endroit pas trop con du genre C:\wamp\bin\php\pear - cree le dissuer "pear"
	Faire php go-pear.phar
Remodifier la variable d'environement (system => "path") pour pear : meme logique qu'avec php.
	Exemple (completer) : [etc...];C:\wamp\bin\php\php5.4.16;C:\wamp\bin\php\pear
Installation de php unit 
Reouvrire le CMD en mode Administrateur !
	pear upgrade pear
	pear channel-discover components.ez.no
	pear channel-discover pear.phpunit.de
	pear channel-discover pear.symfony.com
	pear install --alldeps phpunit/PHPUnit	
##
Ouvrire eclipse.
===
Configuration d'eclipse,
PHP Serveur 
	Windows > Preference > PHP > PHP Executables
	Faire ADD
		Remplir les champs 
		Executable path => ex: C:\wamp\bin\php\php5.4.16\php.exe
		PHP ini => ex: C:\wamp\bin\php\php5.4.16\php.ini
			Verifier que les variable dans le fichier php.ini de Xdebug sois mit sur "on"
				[xdebug]
				xdebug.remote_enable = on
				xdebug.profiler_enable = on
				xdebug.profiler_enable_trigger = on
				xdebug.profiler_output_name = cachegrind.out.%t.%p
				xdebug.profiler_output_dir = "c:/wamp/tmp"
		SAPI Type : CLI
		PHP debugger : XDebug

##
Import serveurpcet eclipse
	Aller dans windows => Show view => Other => Git => Git Repositories
	Dans la vue Git Repositories
		Faire Git Clone
		Copier coller l'URI [gitolite@forge.projetsdetudiants.net:pcetgresivaudan/serveurpcet.git]
		Faire next etc .. le depot ce telecharge
	Le depot apparais dans la vue
		Clique droits sur le dossier Working directory
		Cocher import existing projet => Next => Finish

Activer PHP sur le projet
	Clique droit projet serveurpcet => Configure => Add PHP support

Activer le gestionnaire de package composer
	Clique droit projet serveurpcet => Configure => Add composer support
	Clique droit projet serveurpcet => Properties => Composer
		Cocher : Enable project secific settings
		Configurer : Php executable : (list) etc ...
		Aller dans Composer => Save Action - tous cocher

## composer.json
double clique sur composer.json
Puis Run (fleche verte)

Activer les tests unitaire cote serveur ( dans eclipse ):
	Faire windows  => show view => MakeGood => MakeGood (ok)
	Clique droit projet serveurpcet => Properties => Debug
		Php Debugger : Xdebug
		Seveur : (Default php web server)
		Php Executable : PHP
		Cocher : Enable CLI Debug
		Aller dans MakeGood
			Cocher : PHPUnit
			Dans test Folder :
				Faire add et ajouter "app"
			Preload Script : /serveurpcet/vendor/autoload.php
			Aller dans l'onglet PHPUnit
				XML Configuration file : /serveurpcet/phpunit.xml
	Redemarer Eclipse
===
Configuration Serveur Apache

Changer dans http.conf du serveur apache 
(enlever le comentaire "#")LoadModule rewrite_module modules/mod_rewrite.so 