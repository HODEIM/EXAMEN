
<?php
    class Statistics {

        private $game, $teamName, $teamMembers, $score, $won, $date;
        
        public function __construct ($game, $teamName, $teamMembers, $score, $won, $date){
            $this->game = $game;
            $this->teamName = $teamName;
            $this->teamMembers = $teamMembers;
            $this->score = $score;
            $this->won = $won;
            $this->date = $date;
        }

        public function getGame(){
            return $this->game;
        }

        public function getTeamName(){
            return $this->teamName;
        }

        public function getTeamMembers(){
            return $this->teamMembers;
        }

        public function getScore(){
            return $this->score;
        }

        public function getWon(){
            return $this->won;
        }

        public function getDate(){
            return $this->date;
        }   

        public function __toString(){
            return $this->getGame() . ", " .$this->getTeamName() . ", " . $this->getTeamMembers() . ", " .  $this->getScore() . ", " . $this->getWon() . ", " . $this->getDate() ;
        }

    }

    
    class DBManager {

        ////////////////////////////////
        // CAMBIAR ESTOS 3 PARÁMETROS //
        ////////////////////////////////

        private $servername = "10.14.0.179";
        private $username = "Hodei";
        private $schema = 'Hodei';

        //////////////////////////////
        //////////////////////////////

        private $password = "DW32.Password";
        private $conn;

        function __construct(){
            // Create connection
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->schema);

            // Check connection
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            // echo "Connected successfully";
        }

        public function insertStatistics($statistics) {
            $sql = "INSERT INTO statistics (game, team_name, team_members, score, won, date) VALUES (";
            $sql .= "'" . $statistics->getGame() . "',' " . $statistics->getTeamName() . "',' " . $statistics->getTeamMembers() . "', '" . 
                            $statistics->getScore() . "', '" . $statistics->getWon() . "', '" . $statistics->getDate() . "')";

            if ($this->conn->query($sql) === TRUE) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $this->conn->error;
            }
        }

        public function borrar() {
            $sql = "DELETE FROM statistics";

            if ($this->conn->query($sql) === TRUE) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $this->conn->error;
            }
        }

        public function getStatistics(){
            
            $statsArray = array();

            if ($result = $this->conn->query("SELECT * FROM statistics")) {
                // echo "Returned rows are: " . $result -> num_rows;

                foreach ($result as $row){
                    $game = $row['game'];
                    $teamName = $row['team_name'];
                    $teamMembers = $row['team_members'];
                    $score = $row['score'];
                    $won = $row['won'];
                    $date = $row['date'];

                    $stats = new Statistics($game, $teamName, $teamMembers, $score, $won, $date);
                    $statsArray[] = $stats;
                }
                
                // Free result set
                $result -> free_result();
            }
            return $statsArray;
        }
    }

?>
