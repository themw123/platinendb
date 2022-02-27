<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

require_once("/documents/config/db.php");
require_once("../classes/Login.php");
require_once("../funktion/alle.php");
require_once("../classes/Sicherheit.php");

$login = new Login();

$login_connection= $login->getlogin_connection();
$platinendb_connection = $login->getplatinendb_connection();

//sicherheit checks
$aktion = "platinen";
$von = "platine";
$sicherheit = new Sicherheit($aktion, $von, $login, $login_connection, $platinendb_connection);
$bestanden = $sicherheit->ergebnis();


if($bestanden == true) {
    


    if (isUserEst($platinendb_connection) == true) {
        // DB table to use
        $table = 'platinenviewest';
        
    }
    else {
        //für where anweisung in abfrage
	    $auftraggeber1 = mysqli_real_escape_string($platinendb_connection, $_SESSION['user_name']);

        // DB table to use
        $table = 'platinenview';

        $whereResult = "Auftraggeber = '$auftraggeber1'";
    }

   

    // Table's primary key
    $primaryKey = 'ID';
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes


    $columns = array(
        array( 'db' => 'id',  'dt' => 0 ),
        array( 'db' => 'name',  'dt' => 1 ),
        array( 'db' => 'auftraggeber',  'dt' => 2 ),
        array( 'db' => 'ausstehend',  'dt' => 3 ),
        array( 'db' => 'anzahl',     'dt' => 4 ),
        array( 'db' => 'material', 'dt' => 5 ),
        array( 'db' => 'endkupfer',  'dt' => 6 ),
        array( 'db' => 'staerke',   'dt' => 7 ),
        array( 'db' => 'lagen',     'dt' => 8 ),
        array( 'db' => 'groesse', 'dt' => 9 ),
        array( 'db' => 'oberflaeche',  'dt' => 10 ),
        array( 'db' => 'loetstopp',   'dt' => 11 ),     
        array(
            'db'        => 'erstelltam',
            'dt'        => 12,
            'formatter' => function( $d, $row ) {
                return date( 'd-m-Y', strtotime($d));
            }
        ),
        array(
            'db'        => 'wunschdatum',
            'dt'        => 13,
            'formatter' => function( $d, $row ) {
                return date( 'd-m-Y', strtotime($d));
            }
        ),
        array( 'db' => 'kommentar', 'dt' => 14 ),

    );

    if (isUserEst($platinendb_connection) == true) {
        array_push($columns,
            array( 'db' => 'status', 'dt' => 15 ),
            array( 'db' => 'ignorieren', 'dt' => 16 ),
            array( 'db' => 'abgeschlossenpost', 'dt' => 17 ),
            array( 'db' => 'abgeschlossenfertigung', 'dt' => 18 ),
            array( 'db' => 'downloads1or0', 'dt' => 19 )
        );
    }

    
    // SQL server connection information
    $sql_details = array(
        'user' => DB_USER,
        'pass' => DB_PASS,
        'db'   => DB_NAME_platinendb,
        'host' => '127.0.0.1'
    );
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * If you just want to use the basic configuration for DataTables with PHP
    * server-side, there is no need to edit below this line.
    */
    
    /*
    //mit mysql
    require( '../libraries/ssp.class.php' );
    */

    //mit postgres
    require( '../libraries/ssp.class.postgres.php' );
    
    echo json_encode(
        //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns)
        SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns)
        
    );

}

else {
	$datax[1] = "fehlerhaft";
	header('Content-Type: application/json');
	echo json_encode(array('data'=> $datax));
	die();
}

?>
