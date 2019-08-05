<?php

class SQLiteCreateTable
{

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * create tables
     */
    public function createTables()
    {
        $commands = [
            'DROP TABLE IF EXISTS game_rounds',
            'CREATE TABLE game_rounds (
id   INTEGER PRIMARY KEY,
hand_one VARCHAR(255) NOT NULL,
hand_two VARCHAR(255) NOT NULL,
winning_hand INTEGER NOT NULL
)'        ];

        // execute the sql commands to create new tables
        foreach ($commands as $command) {
            echo $command . PHP_EOL;
            $this->pdo->exec($command);
        }
    }

    /**
     * get the table list in the database
     */
    public function getTableList()
    {
        $stmt = $this->pdo->query("SELECT name
FROM sqlite_master
WHERE type = 'table'
ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = $row['name'];
        }

        return $tables;
    }

    public function getCountWinner($player = 1) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM game_rounds WHERE winning_hand=:player");
        $stmt->execute([':player' => $player]);
        $won = $stmt->fetch();
        echo "Player $player won: " . $won[0] . " games" . PHP_EOL;
    }
}

$script = new SQLiteCreateTable(new \PDO("sqlite:" . __DIR__  . '/../db/db.sqlite3'));
$script->createTables();
//print_r($script->getTableList());
//$script->getCountWinner(1);