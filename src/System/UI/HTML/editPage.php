<?php
/**
 * Loads the pages from the Database
 * */
$pages = elm_Data_GetPages();
?>

<?php
/**
 * Checks if logged in
 * */
if ($_SESSION['login_failure'] == true) {
    /**
     * Checks if the user has permission (create/edit/delete) to view this page
     * */
    if($_SESSION['login_role_fk'] == 1){
        echo    "<h1>Edit Page Content</h1>"
                ."<br><br>"
                ."<a class=\"content_link\" href=\"index.php?addPage_admin\" >Neuen Seite hinzufügen</a>"
                ."<br><br>";
        /**
         * Creates editing/delete management
         * */
        echo "<table class=\"editPage_table\">
                <tr>
                    <th>Seiten ID</th>
                    <th>Seitennamen</th>
                    <th>Actions</th>
                </tr>";

        foreach ($pages as $page) {
            echo "<tr>".
                "<td>".
                $page->id.
                "</td>".

                "<td>".
                $page->name.
                "</td>".

                "<td>".
                "<a class=\"content_link\" href='index.php?id=".$page->id."&editPage_admin'>Editieren</a>".
                "</td>".

                "<td>".
                "<a class=\"content_link\" href='index.php?id=".$page->id."&deletePage_admin'>Löschen</a> ".
                "</td>".
                "</tr>";

        }

    } else {
        echo "<br>";
        echo "You have no right to edit pages.";
    }
}

?>