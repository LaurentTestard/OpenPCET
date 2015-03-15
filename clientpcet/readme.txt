Prerequi
Aller dans sur tuleap => Ma page personnelle => Gestion du compte => editer cle ssh  [https://forge.projetsdetudiants.net/account/editsshkeys.php]
	Suiver les indications ( sans ea pas de commit possible sur les depos )


## Dependance
Telecharger Git [http://git-scm.com/download/win]
	Installer le 

Telecharger Eclipse-PHP [http://kickass.to/eclipse-php-t8841070.html]
	Decompresser le

Telecharger Nodejs [http://nodejs.org/]
	Installer le


##
Import clientpcet eclipse
	Aller dans windows => Show view => Other => Git => Git Repositories
	Dans la vue Git Repositories
		Faire Git Clone
		Copier coller l'URI [gitolite@forge.projetsdetudiants.net:pcetgresivaudan/clientpcet.git]
		Faire next etc .. le depot ce telecharge
	Le depot apparais dans la vue
		Clique droits sur le dossier Working directory
		Cocher the new project wizard => Finnish
			Javascript => Javascript Project
			Next
			Project name : clientpcet
			Cocher : Create project frome existing source
				Direcory metre le dossier ou c'est telecharger le depot git
					Exemple : [C:\Users\Vincent\git\clientpcet]
			Finnish
Configure angularjs facete
	Clique droits sur le projet "clientpcet" 
		Configure =>  Angularjs

[Prerequi : Nodejs doit etre installer ]
Logiciel d'execution des tests
Ouvrez une console en mode administrateur et faite [ex :  C:\Users\Vincent\git\clientpcet]:
Ou clique droits sur le dossier => easy shell => open ( eclipse doit etre ouvert en mode administrateur
npm install 
npm install -g karma

