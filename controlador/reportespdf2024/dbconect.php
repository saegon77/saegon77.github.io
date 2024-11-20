<?php
            Class dbConexion{
                /* Variables de conexion */
                var $dbhost = "localhost";
                var  $username = "root";
                var $password = "";
                var $dbname = "dakara";
                var $conn;
                //Funcion de conexion MySQL
                function getConexion() {
                    $con = mysqli_connect($this->dbhost, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());
            
                    /* Revisamos la conexion */
                    if (mysqli_connect_errno()) {
                        printf("Connect failed: %s\n", mysqli_connect_error());
                        exit();
                    } else {
                        $this->conn = $con;
                    }
                    return $this->conn;
                }
            }
            ?>