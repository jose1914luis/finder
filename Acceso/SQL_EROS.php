<?php

/**
 * Description of Insertar
 *
 * @author josegh
 */
require_once 'Database.php';

class SQL_EROS {

    private $lastId = -1;

    public function lastId() {
        return $this->lastId;
    }

    /* @parametros:
     * @$table: tabla para realizar el delete
     * @$where: valores para constrir el where $where = ['col1'=>['operator', 'value'] ejemplo
     * $where = ['id_col'=>['=', 'id'] y si es anidado $where = ['id_col'=>['=', 'id', 'grupo_1' =>[ (*), 'OR' ['id_col'=>['=', 'id']]]        
     * @$limit: limite numerico de seleccion, $limit > 0 para seleccionar varios, $limit = 0 para 
     * eliminar todos
     */

    public function delete($table, $where, $limit, $show = false) {

        $delete = "DELETE FROM $table";

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $question = "";
        $ban = true;
        $data = array();
//        print_r($where);
        if (isset($where)) {
            if ($show == true) {
                print_r($where);
            }
            foreach ($where as $key => $value) {


                if (isset($value[1])) {

                    if ($value[0] == '(*)') {

                        $node = $value[2];
                        $ban2 = true;
                        foreach ($node as $key2 => $value2) {

                            if (isset($value2[1])) {
                                if ($ban) {
                                    $question .= ($ban2) ? (" WHERE (" . $key2 . " " . $value2[0] . " " . "?") : ( " " . $value[1] . " " . $key2 . " " . $value2[0] . " " . "?");
                                } else {
                                    $question .= ($ban2) ? " AND (" . ($key2 . " " . $value2[0] . " " . "?") : " " . $value[1] . " " . ($key2 . " " . $value2[0] . " " . "?" );
                                }
                                if ($show == true) {
                                    print_r($value2[1]);
                                }

                                array_push($data, $value2[1]);
                                $ban2 = false;
                            }
                        }
                        if (!$ban2)
                            $question .= ")";
                    } else {
                        $question .= ($ban) ? (" WHERE " . $key . " " . $value[0] . " " . "?") : " AND " . ($key . " " . $value[0] . " " . "?" );

                        array_push($data, $value[1]);
                    }

                    $ban = false;
                }
            }
        } else {
            return false;
        }

        $delete .= $question;

        if ($limit > 0) {
            $delete = $delete . " LIMIT " . $limit;
        }

        $query = $pdo->prepare($delete);

        try {

            $result = $query->execute($data);
        } catch (PDOException $exc) {

            echo $exc->getTraceAsString();
        }

        if (!empty($result)) {

            Database::disconnect();
            return $result;
        } else {

            return false;
        }
    }

    /* @parametros:
     * @$table: tabla para realizar el select
     * @$values: valores a seleccionar $values = ['col1', 'col2', 'col3'];
     * @$where: valores para constrir el where $where = ['col1'=>['operator', 'value'] ejemplo
     * $where = ['id_col'=>['=', 'id'] y si es anidado $where = ['id_col'=>['=', 'id' , 'grupo_1' =>[ (*), 'OR' ['id_col'=>['=', 'id']]]        
     * @$limit: limite numerico de seleccion, $limit > 0 para seleccionar varios, $limit = 0 para 
     * seleccionar todos
     * @$offset: limite superior de seleccion, $offset > 0 para seleccionar intervalo, $offset = 0 sin intervalos
     * @$order: si lleva un ordenamiento $order = ['col1' => 'desc'], $order = ['col1' => 'asc']
     * @$mode: $mode = 'all' retorna un array de columnas, $mode = 'one' devuelve una sola fila     
     */

    public function select($table, $values, $where, $limit, $offset, $order, $mode, $show = false) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $colums = "";
        $ban = true;
        foreach ($values as $key => $value) {
            $colums .= ($ban) ? $value : ", " . $value;
            $ban = false;
        }

        $select = "SELECT " . $colums . " FROM " . $table;
        $question = "";
        $ban = true;
        $data = array();
        if (isset($where)) {
//            if ($show == true) {
//                print_r($where);
//            }
            foreach ($where as $key => $value) {


                if (isset($value[1])) {

                    if ($value[0] == '(*)') {

                        $node = $value[2];
                        $ban2 = true;
                        foreach ($node as $key2 => $value2) {

                            if (isset($value2[1])) {
                                if ($ban) {
                                    $question .= ($ban2) ? (" WHERE (" . $key2 . " " . $value2[0] . " " . "?") : ( " " . $value[1] . " " . $key2 . " " . $value2[0] . " " . "?");
                                } else {
                                    $question .= ($ban2) ? " AND (" . ($key2 . " " . $value2[0] . " " . "?") : " " . $value[1] . " " . ($key2 . " " . $value2[0] . " " . "?" );
                                }
                                if ($show == true) {
                                    print_r($value2[1]);
                                }

                                array_push($data, $value2[1]);
                                $ban2 = false;
                            }
                        }
                        if (!$ban2)
                            $question .= ")";
                    } else {
                        $question .= ($ban) ? (" WHERE " . $key . " " . $value[0] . " " . "?") : " AND " . ($key . " " . $value[0] . " " . "?" );

                        array_push($data, $value[1]);
                    }

                    $ban = false;
                }
            }
        }

        $select .= $question;

        $sort = "";
        $ban = true;
        if (isset($order)) {
            foreach ($order as $key => $value) {

                $sort .= ($ban) ? (" ORDER BY " . $key . " " . $value) : ", " . $key . " " . $value;
                $ban = false;
            }
        }

        if ($show == true) {
            print_r($data);
            print_r($select);
            echo '<br>';
        }
        //echo $select;
        $select .= $sort;

        if ($limit > 0) {
            $select = $select . " LIMIT " . $limit;
        }

        if ($offset > 0) {
            $select = $select . " OFFSET " . $offset;
        }


        $query = $pdo->prepare($select);

        try {

            $query->execute($data);
            if ($mode == 'all') {

                $result = $query->fetchAll();
            } else {
                $result = $query->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $exc) {

            echo $exc->getTraceAsString();
        }



        if (!empty($result)) {

            Database::disconnect();
            return $result;
        } else {

            return false;
        }
    }

    /* @parametros:
     * @$table: tabla para realizar el insert
     * @$values: valores a seleccionar $values = ['col1' => 'value'];     
     */

    public function insertar($table, $values) {

        $result = 0;
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $insert = "INSERT INTO " . $table;

        $colums = "";
        $ban = true;
        $data = "";
        foreach ($values as $key => $value) {
            $colums .= ($ban) ? $key : ", " . $key;
            $data .= ($ban) ? ":" . $key : ", :" . $key;
            $ban = false;
        }
        $insert .= "(" . $colums . ") ";
        $insert .= "VALUES(" . $data . ") ";

        $pdo->beginTransaction();
        $stmt = $pdo->prepare($insert);

        try {

            $result = $stmt->execute($values);
            $this->lastId = $pdo->lastInsertId();
            $pdo->commit();
        } catch (PDOException $ex) {

            $pdo->rollBack();
            $result = $ex->getCode();
        }

        Database::disconnect();

        return $result;
    }

    public function update($table, $values, $where) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $update = "UPDATE " . $table;
        //set fecha_inicio =  (select curdate()) where idanuncio = 57

        $set = "";
        $ban = true;
        $data = array();
        foreach ($values as $key => $value) {
            $set .= ($ban) ? " SET " . $key . " = ?" : ", " . $key . " = ?";
            $ban = false;
            array_push($data, $value);
        }

        $update .= $set;
        $question = "";
        $ban = true;

        if (isset($where)) {
            foreach ($where as $key => $value) {

                $question .= ($ban) ? (" WHERE " . $key . $value[0] . "?") : " AND " . ($key . $value[0] . ":" . $key);

                array_push($data, $value[1]);
                $ban = false;
            }
        }
        $update .= $question;
        $stmt = $pdo->prepare($update);

        try {

            $result = $stmt->execute($data);
        } catch (PDOException $ex) {

            $result = $ex;
        }

        Database::disconnect();

        return $result;
    }

}
