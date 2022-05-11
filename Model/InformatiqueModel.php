<?php
include_once 'Model/Model.php';

class InformatiqueModel extends Model {

    /**
     * récupération de tous les emplacements sauf emplacements mémo
     * 
     * @return array
     */
    public function getAllEmplacements() {
        $req = $this->connexion->prepare("SELECT * FROM emplacement WHERE NOT section = 'memo' ORDER BY service");
        $result = $req->execute();

        $emp = array();
        if($result){
            $emp = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $emp;
    }

    /**
     * @param int $id
     * @param int $emp
     * 
     * déplacement d'un post d'un emplacement à un autre
     * 
     */
    public function multipleMove($id, $emp) {
        $req = $this->connexion->prepare("UPDATE post SET id_emplacement=:emp WHERE id=:id");
        $req->bindParam(':id', $id);
        $req->bindParam(':emp', $emp);
        $req->execute();
    }

    /**
     * déplacement multiple d'un tableau d'identifiants
     * 
     */
    public function mgmtMovePosts() {
        if (isset($_POST['mgmtPost'])) {
            foreach ($_POST['mgmtPost'] as $id) {
                $this->multipleMove($id, $_POST['nvService']);
            }
        } 
    }

    /**
     * compte du nombre de posts par emplacement
     * 
     * @return array
     */
    public function nbPostsBySection() {
        $req = $this->connexion->prepare("SELECT id_emplacement, COUNT(*) AS total FROM post GROUP BY id_emplacement");
        $res = $req->execute();
        $nb = array();
        if($res) {
            $nb = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $nb;
    }

    /**
     * @param int $id
     * 
     * suppression d'un emplacement
     * 
     */
    public function multipleDeleteSection($id) {
        $req = $this->connexion->prepare("DELETE FROM emplacement WHERE id=:id");
        $req->bindParam(':id', $id);
        $req->execute();
    }

    /**
     * @param int $idSection
     * 
     * suppression d'un emplacement
     * 
     */
    public function deletePostBySection($idSection) {
        $req = $this->connexion->prepare("DELETE FROM post WHERE id_emplacement=:idSection");
        $req->bindParam(':idSection', $idSection);
        $req->execute();
    }

    /**
     * suppression d'emplacements depuis un tableau d'id
     * 
     */
    public function mgmtDeleteSections() {
        if (isset($_POST['mgmtEmp'])) {
            foreach($_POST['mgmtEmp'] as $id) {
                $this->deletePostBySection($id);
                $this->multipleDeleteSection($id);
            }
        }
    }

    public function mgmtNvSection() {
        $req = $this->connexion->prepare('INSERT INTO emplacement (id, service, section) VALUES (NULL, :serv, :sec)');
        $req->bindParam(':serv', $serv);
        $req->bindParam(':sec', $sec);

        $serv = $_POST['service'];
        $sec = $_POST['section'];
        $req->execute();
    }
}