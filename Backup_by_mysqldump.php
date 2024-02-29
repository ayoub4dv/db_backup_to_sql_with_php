<?php
    /** Variables */
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'dbname';
    $saveDir = 'db_backup_sql';
    $tablesExclude = [          // array, The names of the tables you do not want backed up

    ];


    // Set the timezone to Asia/Tehran
    date_default_timezone_set('Asia/Tehran');
    $thisTime = date('Y-m-d_H-i-s');
    
    /** create subdir time */
    mkdir("$saveDir/$thisTime");

    /** path to save files */
    $saveDir = "$saveDir/$thisTime";
    
    /** Connect to the database */
    $conn = new mysqli($host, $username, $password, $dbname);

    /** Check the connection */
    if ($conn->connect_error) {
        die("Error connecting to the database: " . $conn->connect_error);
    }

    /** get all tables list */
    $tables = [];
    $tblQuery = $conn->query("SHOW TABLES");
    foreach ($tblQuery as $val) {
        foreach ($val as $row) {
            $tables[] = $row;
        }
    }

    $tblBackuped = '';
    
    foreach($tables as $tbl){

        /** search table name for allow backup */
        if (!in_array($tbl, $tablesExclude)) {

            /** Use mysqldump to create a backup of a specific table */
            $command = "mysqldump --host=$host --user=$username --password=$password --databases $dbname --tables $tbl > $saveDir/$tbl.sql";
            exec($command);

            $tblBackuped .= "$tbl \n ";
        }
    }

    /** print details */
    echo "Backup of tables \n $tblBackuped successfully!";

    /** Close the connection */
    $conn->close();

?>
