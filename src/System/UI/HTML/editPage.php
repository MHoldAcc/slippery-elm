<?php
$pages = elm_Data_GetPages();
?>
    <h1>Edit Page Content</h1>
    <br><br>
    <a class="content_link" href="index.php?addPage_admin" >Neuen Seite hinzufügen</a>
    <br><br>
<?php
if(true){

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
?>