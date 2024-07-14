<?php
function filterTracks(){
    $sql = "SELECT * FROM tracks";
    if (sizeof($_GET) > 0) {
        if (isset($_GET['name']) && $_GET['name'] != "") {
            $sql .= " WHERE `name` LIKE '%{$_GET['name']}%'";
        }
    
        if (isset($_GET['artist']) && $_GET['artist'] != "") {
            if (isset($_GET['name']) && $_GET['name'] != "") {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
            }
            $sql .= "`authors` LIKE '%{$_GET['artist']}%'";
        }
        if (isset($_GET['search_sort'])) {
            $sort = "";
            switch ($_GET['search_sort']) {
                case 'name asc':
                    $sort = "`name` ASC";
                    break;
                case 'name desc':
                    $sort = "`name` DESC";
                    break;
                case 'date asc':
                    $sort = "`date` ASC";
                    break;
                case 'date desc':
                    $sort = "`date` DESC";
                    break;
                default:
                    $sort = "`date` DESC";
                    break;
            }
            $sql .= " ORDER BY {$sort};";
        }
    }
    return $sql;
}
function filterAlbums(){
    $sql = "SELECT * FROM albums";
    if (sizeof($_GET) > 0) {
        if (isset($_GET['name']) && $_GET['name'] != "") {
            $sql .= " WHERE `name` LIKE '%{$_GET['name']}%'";
        }
        if (isset($_GET['search_sort'])) {
            $sort = "";
            switch ($_GET['search_sort']) {
                case 'name asc':
                    $sort = "`name` ASC";
                    break;
                case 'name desc':
                    $sort = "`name` DESC";
                    break;
                case 'date asc':
                    $sort = "`date` ASC";
                    break;
                case 'date desc':
                    $sort = "`date` DESC";
                    break;
                default:
                    $sort = "`name`";
                    break;
            }
            $sql .= " ORDER BY {$sort};";
        }
    }
    return $sql;
}
?>