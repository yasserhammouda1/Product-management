<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pentasi";
$conn = new mysqli($servername, $username, $password, $dbname);
function verify($conn, $nom_c, $address_c, $email_c, $nu_phone){
    $sel = "SELECT nom_c, address_c, email_c, nu_phone FROM customer ";
    $result = $conn->query($sel);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["nom_c"] == $nom_c && $row["address_c"] == $address_c && $row["email_c"] == $email_c && $row["nu_phone"] == $nu_phone) {
                return true;
            }
        }
    }
    return false;
}
function verify_co($conn, $nom_c){
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM customer WHERE nom_c = ?");
    $stmt->bind_param("s", $nom_c);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $stmt->close();
    return $count > 0;
}
if ($conn) {
    if (isset($_POST['addpro'])) {
        $descri_p = $_POST['descri_p'];
        $nom_pro = $_POST['nom_pro'];
        $prix = $_POST['prix'];
        $cate_p = $_POST['cate_p'];
        $stmt = $conn->prepare("INSERT INTO product(nom_p, descri_p, prix, cate_p) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds",$nom_pro, $descri_p, $prix, $cate_p);
        $stmt->execute();
        $stmt->close();
        header("Location: miniprojet.html");
        exit();
    }if (isset($_POST['addco'])) {
        $nom_c = $_POST['nom_c'];
        $address_c = $_POST['address_c'];
        $email_c = $_POST['email_c'];
        $nu_phone = $_POST['nu_phone'];
        if (!verify($conn, $nom_c, $address_c, $email_c, $nu_phone)) {
            $stmt = $conn->prepare("INSERT INTO customer(nom_c, address_c, email_c, nu_phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nom_c, $address_c, $email_c, $nu_phone);
            $stmt->execute();
            $stmt->close();
            header("Location: miniprojet.html");
            exit();
        }else{
            echo "cet customer il exist";
        }
    }if(isset($_POST['delet_c'])){
        $nom_c_de = $_POST['nom_c_de'];
    
        $stmt1 = $conn->prepare("DELETE FROM Order_Products WHERE id_o IN (SELECT ID_o FROM commande WHERE id_c IN (SELECT ID_c FROM customer WHERE nom_c = ?))");
        $stmt1->bind_param("s", $nom_c_de);
        $stmt1->execute();
        $stmt1->close();
        
        $stmt2 = $conn->prepare("DELETE FROM commande WHERE id_c IN (SELECT ID_c FROM customer WHERE nom_c = ?)");
        $stmt2->bind_param("s", $nom_c_de);
        $stmt2->execute();
        $stmt2->close();
    
        $stmt3 = $conn->prepare("DELETE FROM customer WHERE nom_c = ?");
        $stmt3->bind_param("s", $nom_c_de);
        $stmt3->execute();
        $stmt3->close();
    
        header("Location: miniprojet.html");
        exit();
    }if(isset($_POST['delet_p'])){
        $id_p = $_POST['id_p'];
        $stmt = $conn->prepare("DELETE FROM product WHERE ID_p = ?");
        $stmt->bind_param("s",$id_p );
        $stmt->execute();        
        $stmt->close();
        header("Location: miniprojet.html");
        exit(); 
    }if (isset($_POST['cherche'])) {
        $nom_l = $_POST['nom_li'];
        if (verify_co($conn, $nom_l)) {
            $id_cost_stmt = $conn->prepare("SELECT ID_c FROM customer WHERE nom_c = ?");
            $id_cost_stmt->bind_param("s", $nom_l);
            $id_cost_stmt->execute();
            $id_cost_result = $id_cost_stmt->get_result();
            $id_cost_row = $id_cost_result->fetch_assoc();
            $id_cost_stmt->close();
    
            if ($id_cost_row) {
                $id_cost = $id_cost_row['ID_c'];
    
                $orders_stmt = $conn->prepare(" SELECT op.id_e, op.quentité, p.nom_p 
                FROM Order_Products op 
                INNER JOIN commande c ON op.id_o = c.ID_o 
                INNER JOIN product p ON c.id_p = p.ID_p 
                INNER JOIN expidition e ON op.id_e = e.ID_e
                WHERE c.id_c = ? AND e.statut_e = 'en attand'                
                ");
                $orders_stmt->bind_param("i", $id_cost);
                $orders_stmt->execute();
                $orders_result = $orders_stmt->get_result();
    
                if ($orders_result->num_rows > 0) {
                    echo "<div class='result-box'>";
                    echo "<p>Résultat de la recherche: </p>";
                    echo "<ul>";
                    while ($order_row = $orders_result->fetch_assoc()) {
                        echo "<li>Numéro de commande: " . $order_row['id_e'] . "</li>";
                        echo "<ul>";
                        echo "<li>Produit: " . $order_row['nom_p'] . "</li>";
                        echo "<li>Quantité: " . $order_row['quentité'] . "</li>";
                        echo "</ul>";
                    }
                    echo "</ul>";
                    echo "</div>";
                } else {
                    echo "<p>Aucun résultat trouvé pour les commandes en attente.</p>";
                }
                $orders_stmt->close();
            } else {
                echo "<p>Aucun résultat trouvé pour le client.</p>";
            }
        }
    }if(isset($_POST['addcommande'])) {
        $nom_co = $_POST['nom_co'];
        if (verify_co($conn, $nom_co)) {
            $address_cos = $_POST['address_cos'];
            $no_product = $_POST['no_product'];
            $data_c = $_POST['data_c'];
            $Quentité_c = $_POST['Quentité_c'];
    
            $iddeco_stmt = $conn->prepare("SELECT ID_c FROM customer WHERE nom_c = ?");
            $iddeco_stmt->bind_param("s", $nom_co);
            $iddeco_stmt->execute();
            $iddeco_result = $iddeco_stmt->get_result();
            $iddeco_row = $iddeco_result->fetch_assoc();
            $iddeco = $iddeco_row['ID_c'];
            $iddeco_stmt->close();
    
            $iddepro_stmt = $conn->prepare("SELECT ID_p FROM product WHERE nom_p = ?");
            $iddepro_stmt->bind_param("s", $no_product);
            $iddepro_stmt->execute();
            $iddepro_result = $iddepro_stmt->get_result();
            $iddepro_row = $iddepro_result->fetch_assoc();
            $iddepro = $iddepro_row['ID_p'];
            $iddepro_stmt->close();
    
            $ajou = $conn->prepare("INSERT INTO expidition(date_e, address_e, statut_e) values(?, ?, 'en attand')");
            $ajou->bind_param("ss", $data_c, $address_cos);
            $ajou->execute();
            $id_e = $conn->insert_id;
            $ajou->close();

            $ajou = $conn->prepare("INSERT INTO commande(date_o, statut_o, id_p, id_c) values(?, 'pending', ?, ?)");
            $ajou->bind_param("sii", $data_c, $iddepro, $iddeco);
            $ajou->execute();
            $id_cos = $conn->insert_id;
            $ajou->close();
    
            $ajou = $conn->prepare("INSERT INTO Order_Products(id_e, id_o, quentité) values(?, ?, ?)");
            $ajou->bind_param("iii", $id_e, $id_cos, $Quentité_c);
            $ajou->execute();
            $ajou->close();
            header("Location: miniprojet.html");
            exit(); 
        }
    }if(isset($_POST['result_cat'])) {
        $nom_cat = $_POST['nom_cat'];
        $data_cat_s = $_POST['data_cat_s'];
        $data_cat_f = $_POST['data_cat_f'];
    
        $query = "SELECT p.nom_p, p.descri_p, p.prix, c.date_o, c.statut_o 
                  FROM product p
                  INNER JOIN commande c ON p.ID_p = c.id_p
                  WHERE p.cate_p = ? AND c.date_o BETWEEN ? AND ?";
    
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sss", $nom_cat, $data_cat_s, $data_cat_f);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                echo "<div id='result_cata'>";
                echo "<h2>Résultats des ventes pour le catalogue: " . htmlspecialchars($nom_cat) . "</h2>";
                echo "<style>";
                echo "
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
    
                    table th, table td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
    
                    table th {
                        background-color: #f2f2f2;
                    }
    
                    table tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
    
                    table td:nth-child(3) {
                        font-weight: bold;
                    }
    
                    table td:nth-child(4) {
                        font-style: italic;
                    }
                ";
                echo "</style>";
                echo "<table>";
                echo "<tr><th>Nom du Produit</th><th>Description</th><th>Prix</th><th>Date de Vente</th><th>Statut</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom_p']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descri_p']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['prix']) . "DA</td>";
                    echo "<td>" . htmlspecialchars($row['date_o']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['statut_o']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>Aucune vente trouvée pour ce catalogue pendant la période spécifiée.</p>";
            }
            $stmt->close();
        }
    }if(isset($_POST['update_c'])) {
        $nom_c = $_POST['nom_c'];
        $stmt = $conn->prepare("SELECT ID_c FROM customer WHERE nom_c = ?");
        $stmt->bind_param("s", $nom_c);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_c = $row['ID_c'];
            $updates = [];
    if (!empty($_POST['nom_c'])) {
        $updates[] = "nom_c = '" . $_POST['nom_c'] . "'";
    }
    if (!empty($_POST['address_c'])) {
        $updates[] = "address_c = '" . $_POST['address_c'] . "'";
    }
    if (!empty($_POST['email_c'])) {
        $updates[] = "email_c = '" . $_POST['email_c'] . "'";
    }
    if (!empty($_POST['nu_phone'])) {
        $updates[] = "nu_phone = '" . $_POST['nu_phone'] . "'";
    }
    
    if (!empty($updates)) {
        $update_query = "UPDATE customer SET " . implode(", ", $updates) . " WHERE ID_c = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $id_c);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: miniprojet.html");
    exit();
        } else {
            echo "cet customer il n'est exist pas";
        }
    
        $stmt->close();
    }if (isset($_POST['update_p'])) {
        $nom_p = $_POST['nom_p'];
    
        $stmt = $conn->prepare("SELECT ID_p FROM product WHERE nom_p = ?");
        $stmt->bind_param("s", $nom_p);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_p = $row['ID_p'];
            $updates = [];
            if (!empty($_POST['nom_p'])) {
                $updates[] = "nom_p = '" . $_POST['nom_p'] . "'";
            }
            if (!empty($_POST['descri_p'])) {
                $updates[] = "descri_p = '" . $_POST['descri_p'] . "'";
            }
            if (!empty($_POST['cate_p'])) {
                $updates[] = "cate_p = '" . $_POST['cate_p'] . "'";
            }
            if (!empty($_POST['prix'])) {
                $updates[] = "prix = '" . $_POST['prix'] . "'";
            }
    
            if (!empty($updates)) {
                $update_query = "UPDATE product SET " . implode(", ", $updates) . " WHERE ID_p = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("i", $id_p);
                $stmt->execute();
                $stmt->close();
            }
    
            header("Location: miniprojet.html");
            exit();
        } else {
            echo "cet product il n'est exist pas";
        }
    
        $stmt->close();
    }
}
$conn->close();
?>