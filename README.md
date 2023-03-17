# API_REST

# Projet : Conception et développement d’API RESpour la gestion d’articles

L’objectif général de ce projet est de proposer une solution pour la gestion d’articles de blogs. Il s’agit en particulier de concevoir le backend de la solution, c’est-à-dire la partie cachée aux utilisateurs de l’application. La solution devra s’appuyer sur une architecture orientée ressources, c’est-à-dire sur une
ou plusieurs API REST.
Le projet doit être réalisé en binôme, et le binôme doit être constitué d’étudiants du même groupe. Si le
nombre d’étudiants dans le groupe est impair, seul un trinôme peut être constitué.

## Cahier des charges fonctionnel

### Fonctions principales
La solution à proposer est constituée de 3 fonctions principales :\n
  ● La publication, la consultation, la modification et la suppression des articles de blogs. Un article est caractérisé, a minima, par sa date de publication, son         auteur et son contenu.
  ● L’authentification des utilisateurs souhaitant interagir avec les articles. Cette fonctionnalité devra
    s’appuyer sur les JSON Web Token (JWT). Un utilisateur est caractérisé, a minima, par un nom d’utilisateur, un mot de passe et un rôle (moderator ou publisher).
  ● La possibilité de liker/disliker un article. La solution doit permettre de retrouver quel(s) utilisateur(s) a liké/disliké un article. Gestion des restriction    d’accès

L’application doit mettre en place des restrictions concernant la manipulation des articles. Ces restrictions doivent s’appuyer sur le rôle de l’utilisateur, et mettre en oeuvre les exigences suivantes :

● Un utilisateur authentifié avec le rôle moderator peut :
  ○ Consulter n’importe quel article. Un utilisateur moderator doit accéder à l’ensemble des informations décrivant un article : auteur, date de publication, contenu, liste des utilisateurs ayant liké l’article, nombre total de like, liste des utilisateurs ayant disliké l’article, nombre total de dislike.
    ○ Supprimer n’importe quel article.
● Un utilisateur authentifié avec le rôle publisher peut :
    ○ Poster un nouvel article.
    ○ Consulter ses propres articles.
    ○ Consulter les articles publiés par les autres utilisateurs. Un utilisateur publisher doit accéder aux informations suivantes relatives à un article : auteur, date de publication, contenu, nombre total de like, nombre total de dislike.
    ○ Modifier les articles dont il est l’auteur.
    ○ Supprimer les articles dont il est l’auteur.
    ○ Liker/disliker les articles publiés par les autres utilisateurs.
● Un utilisateur non authentifié peut :
    ○ Consulter les articles existants. Seules les informations suivantes doivent être disponibles : auteur, date de publication, contenu.

### Gestion des erreurs
Une attention particulière devra être proposée concernant la gestion des erreurs retournées par votre/vos API REST : les applications clientes exploitant votre solution doivent être en mesure, à partir des erreurs retournées lors d’envois de requêtes, d’identifier le problème rencontré et d’y remédier.

### Fonctions optionnelles
● Proposition d’une interface utilisateur permettant d’interagir avec le backend que vous avez conçu et développé.

### Travail à réaliser
● Conception de la base de données ;
● Conception de l’architecture logicielle du backend fondée sur une ou plusieurs API REST ;
● Développement du backend ;
● Proposition d’un ensemble de requêtes clientes démontrant que le cahier des charges est respecté (des précisions sont données dans les documents à rendre ci-dessous).
