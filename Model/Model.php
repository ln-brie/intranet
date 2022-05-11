<?php
class Model
{
    const SERVER = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const BASE = 'intranet';

    
    /*const SERVER = 'fr-ner-ora';
    const USER = 'INTRA';
    const PASSWORD = 'Intranet0';
    const BASE = 'INTRAPROD';*/
    

    protected $connexion;

    public function __construct()
    {
        try {
            //$this->connexion = new PDO('oci:dbname=//'. self::SERVER . ':1521/'.self::BASE, self::USER, self::PASSWORD);
            $this->connexion = new PDO('mysql:host='. self::SERVER.'; dbname='. self::BASE.';charset=UTF8', self::USER, self::PASSWORD);
        } catch (Exception $e) {
            die('Erreur:' . $e->getMessage());
        }
    }

    /**
     * @return array
     * 
     * récupération des entrées de la bdd pour affichage des posts
     */
    public function getPosts()
    {
        $req = $this->connexion->prepare("SELECT a.id, a.titre, a.contenu, b.section FROM post AS a JOIN emplacement AS b ON a.id_emplacement = b.id WHERE b.service = :serv AND a.brouillon='0' AND NOT b.section='memo' ORDER BY b.section ASC, CASE WHEN date_update IS NULL THEN date_ajout WHEN date_update > date_ajout THEN date_update END DESC");
        $req->bindParam(':serv', $_GET['page']);
        $resultat = $req->execute();
        $posts = array();
        if ($resultat) {
            $posts = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $posts;
    }

    /**
     * tri des entrées d'un tableau selon un attribut
     * 
     * @param string $key
     * @param array $array
     * 
     * @return array
     * 
     */
    public function group($key, $array)
    {
        $result = array();
        foreach ($array as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[''][] = $val;
            }
        }
        return $result;
    }


    /**
     * @return array
     * 
     * récupération des données d'un post correspondant à un id donné
     */
    public function getPostById()
    {
        $req = $this->connexion->prepare("SELECT id, titre, contenu, exclu, home, brouillon, diffusion, id_emplacement FROM post WHERE id=:id");
        $req->bindParam(':id', $id);

        $id = $_GET['id'];
        $resultat = $req->execute();
        $post = array();
        if ($resultat) {
            $post = $req->fetch(PDO::FETCH_ASSOC);
        }
        return $post;
    }

    /**
     * ajout d'un post à la base de données
     */
    public function addPost()
    {

        $requete = $this->connexion->prepare("INSERT INTO post (titre, contenu, id_emplacement, exclu, diffusion, brouillon) VALUES (:titre, :contenu, :emplacement, :exclu, :diff, :brouillon)");

        $requete->bindParam(':titre', $titre);
        $requete->bindParam(':contenu', $contenu);
        $requete->bindParam(':emplacement', $emplacement);
        $requete->bindParam(':exclu', $exclusion);
        $requete->bindParam(':diff', $diffusion);
        $requete->bindParam(':brouillon', $brouillon);

        $titre = (isset($_POST['titre'])) ? $_POST['titre'] : 'titre';
        $contenu = (isset($_POST['contenu'])) ? $_POST['contenu'] : 'contenu';
        $emplacement = $_POST['emplacement'];
        $exclusion = (isset($_POST['exclure'])) ? $_POST['exclure'] : '0';
        $diffusion = (isset($_POST['diffusion'])) ? $_POST['diffusion'] : '0';
        $brouillon = (isset($_POST['brouillon'])) ? $_POST['brouillon'] : '0';

        $requete->execute();
    }

    /**
     * @param string $service
     * 
     * @return array
     * 
     * récupération de la liste des emplacements possibles pour un service donné
     */
    public function getEmplacements($service)
    {
        $requete = $this->connexion->prepare("SELECT * FROM emplacement WHERE service=:service AND NOT section='memo'");
        $requete->bindParam(':service', $service);
        $resultat = $requete->execute();

        $emp = array();
        if ($resultat) {
            $emp = $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        return $emp;
    }

    /**
     * mise à jour d'un post dans la bdd
     */
    public function updatePost()
    {
        $requete = $this->connexion->prepare("UPDATE post SET titre=:titre, contenu=:contenu, id_emplacement=:emplacement, date_update=now(), exclu=:exclu, brouillon=:brouillon, diffusion=:diff WHERE id=:id");

        $requete->bindParam(':id', $id);
        $requete->bindParam(':titre', $titre);
        $requete->bindParam(':contenu', $contenu);
        $requete->bindParam(':emplacement', $emplacement);
        $requete->bindParam(':exclu', $exclusion);
        $requete->bindParam(':diff', $diffusion);
        $requete->bindParam(':brouillon', $brouillon);



        $id = $_POST['id'];
        $titre = (isset($_POST['titre'])) ? $_POST['titre'] : 'titre';
        $contenu = (isset($_POST['contenu'])) ? $_POST['contenu'] : 'contenu';
        $emplacement = (isset($_POST['emplacement'])) ? $_POST['emplacement'] : '1';
        $exclusion = (isset($_POST['exclure'])) ? $_POST['exclure'] : '0';
        $diffusion = (isset($_POST['diffusion'])) ? $_POST['diffusion'] : '0';
        $brouillon = (isset($_POST['brouillon'])) ? $_POST['brouillon'] : '0';



        $requete->execute();
    }

    /**
     * suppression d'une entrée dans la table post
     */
    public function deletePost()
    {
        $requete = $this->connexion->prepare("DELETE FROM post WHERE id=:id");
        $requete->bindParam(':id', $id);

        $id = $_POST['id'];
        $requete->execute();
    }

    /**
     * mise à jour du champ section d'un emplacement
     */
    public function updateSection()
    {
        $req = $this->connexion->prepare("UPDATE emplacement SET section=:section WHERE id=:id");

        $req->bindParam(':section', $section);
        $req->bindParam(':id', $id);

        $section = $_POST['section'];
        $id = $_POST['id'];
        $req->execute();
    }

    /**
     * ajout d'une entrée dans la table emplacement
     */
    public function addSection()
    {
        $req = $this->connexion->prepare("INSERT INTO emplacement (service, section) VALUES (:service, :section)");
        $req->bindParam(':service', $service);
        $req->bindParam(':section', $section);

        $service = $_GET['page'];
        $section = $_POST['nvSec'];
        $result = $req->execute();
    }

    /**
     * récupération des informations d'un emplacement par l'id
     */
    public function getSectionById()
    {
        $req = $this->connexion->prepare("SELECT * FROM emplacement WHERE id=:id");
        $req->bindParam(':id', $id);

        $id = $_GET['id'];
        $result = $req->execute();

        $section = array();

        if ($result) {
            $section = $req->fetch(PDO::FETCH_ASSOC);
        }
        return $section;
    }

    /**
     * récupération des posts associés à un emplacement
     */
    public function getPostsBySection()
    {
        $req = $this->connexion->prepare("SELECT * FROM post WHERE id_emplacement=:id");
        $req->bindParam(':id', $id);

        $id = $_GET['id'];
        $result = $req->execute();

        $posts = array();

        if ($result) {
            $posts = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $posts;
    }

    /**
     * @param int $id
     * 
     * suppression d'une entrée de la table emplacement 
     */
    public function deleteSection($id)
    {
        $req = $this->connexion->prepare("DELETE FROM emplacement WHERE id=:id");
        $req->bindParam(':id', $id);
        $req->execute();
    }

    /**
     * modification de l'emplacement de tous les posts associés à un emplacement donné
     */
    public function movePosts()
    {
        $req = $this->connexion->prepare("UPDATE post SET id_emplacement=:nvid WHERE id_emplacement=:id");
        $req->bindParam(':nvid', $nvId);
        $req->bindParam(':id', $id);

        $nvId = $_POST['destSec'];
        $id = $_POST['idSec'];
        $req->execute();
    }

    /**
     * suppression d'une section et des posts qui lui sont reliés
     */
    public function delPostsDelSection()
    {
        $req = $this->connexion->prepare('DELETE FROM post WHERE id_emplacement=:id');
        $req->bindParam(':id', $id);

        $id = $_POST['idSec'];
        $result = $req->execute();

        if ($result) {
            $this->deleteSection($id);
        }
    }

    /**
     * récupération de tous les posts classés par date (du plus récent au plus ancien)
     */
    public function getAllPostsByDate()
    {
        $req = $this->connexion->prepare("SELECT a.id, a.titre, a.diffusion, a.home, b.service, b.section FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id  WHERE NOT (b.section LIKE 'video%' AND b.service='home') ORDER BY CASE WHEN date_update IS NULL THEN date_ajout WHEN date_update > date_ajout THEN date_update END DESC");
        $result = $req->execute();

        $posts = array();
        if ($result) {
            $posts = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $posts;
    }

    /**
     * récupération de tous les posts classés par service
     */
    public function getAllPosts()
    {
        $req = $this->connexion->prepare("SELECT a.id, a.titre, a.diffusion, a.home, a.brouillon, b.service, b.section FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id WHERE NOT b.section='memo' ORDER BY b.service");
        $result = $req->execute();

        $posts = array();
        if ($result) {
            $posts = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $posts;
    }

    /**
     * @param int $id
     * 
     * mise à jour du champ diffusion d'un post identifié par $id
     */
    public function diffusion($id)
    {
        $req = $this->connexion->prepare('UPDATE post SET diffusion=1 WHERE id = :id');
        $req->bindParam(':id', $id);
        $req->execute();
    }

    public function home($id)
    {
        $reqHome = $this->connexion->prepare('UPDATE post SET home=1 WHERE id = :id');
        $reqHome->bindParam(':id', $id);
        $reqHome->execute();
    }

    /**
     * mise à jour du champ diffusion d'un tableau de posts
     * 
     * mise à 0 du champ diffusion de tous les posts
     * appel de la fonction diffusion() pour chaque élément du tableau récupéré en POST
     */
    public function updateDiffusion()
    {
        $reqNoDiff = $this->connexion->prepare('UPDATE post SET diffusion=0, date_update=date_update WHERE diffusion=1');
        $reqNoDiff->execute();
        $reqNoHome = $this->connexion->prepare('UPDATE post SET home=0, date_update=date_update WHERE home=1');
        $reqNoHome->execute();

        if (isset($_POST['id'])) {
            foreach ($_POST['id'] as $id) {
                $this->diffusion($id);
            }
        }
        if (isset($_POST['home'])) {
            foreach ($_POST['home'] as $home) {
                $this->home($home);
            }
        }
    }

    /**
     * @param string $path
     * @param string $filename
     * 
     * renommage d'un fichier si le nom est déjà pris
     * 
     * @return string
     */
    public function file_newname($path, $filename)
    {
        if ($pos = strrpos($filename, '.')) {
            $name = substr($filename, 0, $pos);
            $ext = substr($filename, $pos);
        } else {
            $name = $filename;
        }

        $newpath = $path . '/' . $filename;
        $newname = $filename;
        $counter = 0;
        while (file_exists($newpath)) {
            $newname = $name . '_' . $counter . $ext;
            $newpath = $path . '/' . $newname;
            $counter++;
        }

        return $newname;
    }

    /**
     * upload d'un fichier dans le dossier docs
     * renommage si besoin, retourne le nom du fichier
     * 
     * @return string
     */
    public function addFile($dossier)
    {
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777);
        }
        $name = $this->file_newname($dossier . '/', $_FILES['file']['name']);

        move_uploaded_file($_FILES['file']['tmp_name'], $dossier . '/' . $name);

        echo $name;
    }

    /**
     * @param string $username
     * @param string $password
     * 
     * identification d'un usager grâce à l'active directory
     * 
     * connexion à l'active directory
     * vérification de l'existence de l'utilisateur
     * vérification que le couple user-mdp est valide
     * retour d'un code correspondant à la situation
     * 
     * @return int
     */
    public function ldap_login($username, $password)
    {
        $host = 0 /*adresse IP du serveur*/;
        $protocol = 'ldap';
        $base_dn = 'OU=Internals,OU=Users,OU=FR-Nersac,DC=adcorp,DC=ners';
        $domain = "@adcorp.ners";

        if ($username && $password) {
            $connection_string = "$protocol://$host";
            $conn = @ldap_connect($connection_string) or $msg = "Could not connect: $connection_string";
            ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

            $ldaprdn = $username . $domain;
            $ldapbind = @ldap_bind($conn, $ldaprdn, $password);
            if ($ldapbind) {
                $search = ldap_search($conn, $base_dn, "(samaccountname=$username)");
                if ($search) {
                    $result = ldap_get_entries($conn, $search);
                    if ($result['count'] > 0) {
                        $returnval = 1; // "Success"
                    } else {
                        $returnval = -1; // "User not found"
                    }
                }
            } else {
                $returnval = 0; // "Incorrect username/password"
            }
        } else {
            $returnval = -1; // "Please enter username/password"
        }

        return $returnval;
    }

    /**
     * @param string $user
     * 
     * récupération du prénom de l'usager
     * 
     * connection à l'AD avec un compte administrateur
     * recherche de l'usager $user et récupération de la valeur du champ givenname
     * 
     * @return string
     */
    public function getPrenom($user)
    {
        $host = 0 /*adresse IP du serveur*/;
        $protocol = 'ldap';
        $base_dn = 'OU=Internals,OU=Users,OU=FR-Nersac,DC=adcorp,DC=ners';
        $domain = "@adcorp.ners";

        // Use admin user in LDAP to query
        $username = 0 /*identifiant admin*/;
        $password = 0 /*mot de passe admin*/;

        // Active Directory server
        $connection_string = "$protocol://$host";

        // Active Directory DN, base path for our querying user
        $ldap_dn = $base_dn;

        // Active Directory user for querying
        $query_user = $username . "$domain";
        $password = $password;

        // Connect to AD
        $ldap = ldap_connect($connection_string) or die("Could not connect to LDAP");
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_bind($ldap, $query_user, $password) or die("Could not bind to LDAP");

        // Search AD
        $results = ldap_search($ldap, $ldap_dn, "(samaccountname=$user)", array("givenname"));
        $entries = ldap_get_entries($ldap, $results);

        // No information found, bad user
        if ($entries['count'] == 0) return false;

        // Get groups and primary group token
        $output = $entries[0]['givenname'];

        // Remove extraneous first entry i.e. the count of the groups the user belongs to
        array_shift($output);

        return $output[0];
    }

    /**
     * @param string $user
     * 
     * récupération des groupes associés à un utilisateur
     * 
     * connexion à l'AD avec un compte administrateur
     * récupération des groupes associés à l'usager $user
     * retour d'un tableau contenant les groupes
     * 
     * @return array
     */
    public function get_groups($user)
    {
        $host = 0 /*adresse IP du serveur*/;
        $protocol = 'ldap';
        $base_dn = 'OU=Internals,OU=Users,OU=FR-Nersac,DC=adcorp,DC=ners';
        $domain = "@adcorp.ners";

        // Use admin user in LDAP to query
        $username = 0 /*identifiant admin*/ ;
        $password = 0 /*mot de passe admin*/;

        // Active Directory server
        $connection_string = "$protocol://$host";

        // Active Directory DN, base path for our querying user
        $ldap_dn = $base_dn;

        // Active Directory user for querying
        $query_user = $username . "$domain";
        $password = $password;

        // Connect to AD
        $ldap = ldap_connect($connection_string) or die("Could not connect to LDAP");
        ldap_bind($ldap, $query_user, $password) or die("Could not bind to LDAP");

        // Search AD
        $results = ldap_search($ldap, $ldap_dn, "(samaccountname=$user)", array("memberof", "primarygroupid"));
        $entries = ldap_get_entries($ldap, $results);

        // No information found, bad user
        if ($entries['count'] == 0) return false;

        // Get groups and primary group token
        $output = $entries[0]['memberof'];
        $token = $entries[0]['primarygroupid'][0];

        // Remove extraneous first entry i.e. the count of the groups the user belongs to
        array_shift($output);

        // We need to look up the primary group, get list of all groups
        $results2 = ldap_search($ldap, $ldap_dn, "(objectcategory=group)", array("distinguishedname", "primarygrouptoken"));
        $entries2 = ldap_get_entries($ldap, $results2);

        // Remove extraneous first entry
        array_shift($entries2);

        // Loop through and find group with a matching primary group token
        foreach ($entries2 as $e) {
            if ($e['primarygrouptoken'][0] == $token) {
                // Primary group found, add it to output array
                $output[] = $e['distinguishedname'][0];
                // Break loop
                break;
            }
        }
        return $output;
    }

    /**
     * login d'un usager et création de variables de session, fonction appelée en ajax
     * 
     * connexion à l'AD, si connexion réussie : création d'une variable de session contenant les groupes,
     * création d'une variable de session contenant le prénom de l'usager
     * attribution des droits sur les services dans variable 'access' contenant un tableau
     * retour d'un message d'état de connexion
     * 
     * @return string
     */
    public function connectAD()
    {
        $user = $_POST['user'];
        $pwd = $_POST['pwd'];
        if ($this->ldap_login($user, $pwd) == 1) {
            $access = array();
            $_SESSION['user'] = $user;
            $_SESSION['group'] = $this->get_groups($user);
            $_SESSION['prenom'] = $this->getPrenom($user);
            function checkDroits($groupes, $nomGroupe, $tableau, $page)
            {
                if (strpos($nomGroupe, $groupes) !== false) {
                    if (in_array($page, $tableau) == false) {
                        array_push($tableau, $page);
                    }
                }
                return $tableau;
            }

            if (!empty($_SESSION['group'])) {
                foreach ($_SESSION['group'] as $g) {
                    if (strpos($g, 'INTRANET_ADMIN') !== false) {
                        $access = array('direction', 'communication', 'secuenv', 'ressourcesh', 'qualite', 'informatique', 'plansprod', 'comptabilite', 'logistique', 'achats', 'galerie', 'applications', 'home');
                    } else if (strpos($g, 'INTRANET_COMM') !== false) {
                        array_push($access, 'communication'); 
                    } else if (strpos($g, 'INTRANET_APPLICATIONS') !== false) {
                        array_push($access, 'applications');
                    } else if (strpos($g, 'INTRANET_GALERIE') !== false) {
                        array_push($access, 'galerie');
                    } else if (strpos($g, 'INTRANET_ACCUEIL') !== false) {
                        array_push($access, 'home');
                    } else if (strpos($g, 'INTRANET_DIRECTION') !== false) {
                        array_push($access, 'direction');
                    } else if (strpos($g, 'INTRANET_RH') !== false) {
                        array_push($access, 'ressourcesh');
                    } else if (strpos($g, 'INTRANET_SECU') !== false) {
                        array_push($access, 'secuenv');
                    } else if (strpos($g, 'INTRANET_QUALITE') !== false) {
                        array_push($access, 'qualite');
                    } else if (strpos($g, 'INTRANET_APPLICATIONS') !== false) {
                        array_push($access, 'applications');
                    } else if (strpos($g, 'INTRANET_INFORMATIQUE') !== false) {
                        array_push($access, 'informatique');
                    } else if (strpos($g, 'INTRANET_PLANSPROD') !== false) {
                        array_push($access, 'plansprod');
                    } else if (strpos($g, 'INTRANET_COMPTA') !== false) {
                        array_push($access, 'comptabilite');
                    } else if (strpos($g, 'INTRANET_LOGISTIQUE') !== false) {
                        array_push($access, 'logistique');
                    } else if (strpos($g, 'INTRANET_ACHATS') !== false) {
                        array_push($access, 'achats');
                    }
                }
            }

            $_SESSION['access'] = $access;
            echo 'Connexion en cours...';
        } elseif ($this->ldap_login($user, $pwd) == -1) {
            echo 'Utilisateur inconnu';
        } elseif ($this->ldap_login($user, $pwd) == 0) {
            echo 'Login ou mot de passe incorrect';
        };
    }

    /**
     * @param int $id
     * 
     * suppression d'un post (appelée dans le cas d'une suppression multiple)
     * 
     */
    public function multipleDelete($id)
    {
        $req = $this->connexion->prepare("DELETE FROM post WHERE id=:id");
        $req->bindParam(':id', $id);
        $req->execute();
    }

    /**
     * suppression de posts depuis un tableau d'id
     * 
     */
    public function mgmtSuppPosts()
    {
        if (isset($_POST['mgmtPost'])) {
            foreach ($_POST['mgmtPost'] as $id) {
                $this->multipleDelete($id);
            };
        }
    }

    /**
     * récupération des posts brouillon (brouillon=1)
     * 
     * @return array
     */
    public function getBrouillons()
    {
        $req = $this->connexion->prepare('SELECT a.id, a.titre, a.contenu, b.section FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id WHERE b.service=:serv AND a.brouillon="1"');
        $req->bindParam(':serv', $serv);

        $serv = isset($_GET['page']) ? $_GET['page'] : 'home';
        $res = $req->execute();

        $list = array();

        if ($res) {
            $list = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $list;
    }

    /**
     * changement de statut d'un post brouillon à un post public (brouillon=0) et redéfinition de la date d'ajout
     * 
     */
    public function pubBrouillon()
    {
        $req = $this->connexion->prepare('UPDATE post SET brouillon="0", date_ajout=now(), date_update=now() WHERE id=:id');
        $req->bindParam(':id', $_GET['id']);
        $req->execute();
    }

    /**
     * récupération du post mémo
     * 
     * @return array
     */
    public function getMemo()
    {
        $req = $this->connexion->prepare('SELECT a.id, a.titre, a.contenu, a.id_emplacement FROM post AS a JOIN emplacement AS b ON a.id_emplacement=b.id WHERE service=:serv AND section="memo"');
        $req->bindParam(':serv', $_GET['page']);
        $res = $req->execute();
        if ($res) {
            $memo = $req->fetch(PDO::FETCH_ASSOC);
        }

        return $memo;
    }

    /**
     * @param string $service
     * @param string $section
     * 
     * ajout d'un post 'rapide" (guides et annuaires)
     * 
     */
    public function addShort($service, $section)
    {
        $reqEmp = $this->connexion->prepare('SELECT id FROM emplacement WHERE service=:serv AND section=:sect');
        $reqEmp->bindParam(':serv', $service);
        $reqEmp->bindParam(':sect', $section);
        $reqEmp->execute();
        $id_emp = $reqEmp->fetch(PDO::FETCH_ASSOC);


        $req = $this->connexion->prepare('INSERT INTO post (id, titre, contenu, id_emplacement) VALUES (NULL, :titre, :contenu, :id_emp)');

        $req->bindParam(':titre', $titre);
        $req->bindParam(':contenu', $contenu);
        $req->bindParam('id_emp', $id_emp['id']);

        $titre = $_POST['titre'];
        $contenu = $_POST['lien'];

        $req->execute();
    }

    /**
     * mise à jour d'un post 'rapide' (guides et annuaires)
     * 
     */
    public function majShort()
    {
        $req = $this->connexion->prepare('UPDATE post SET titre=:titre, contenu=:lien WHERE id=:id ');
        $req->bindParam(':titre', $titre);
        $req->bindParam(':lien', $lien);
        $req->bindParam(':id', $id);

        $titre = $_POST['titre'];
        $lien = $_POST['lien'];
        $id = $_POST['id'];

        $req->execute();
    }
}
