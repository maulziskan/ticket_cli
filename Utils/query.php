<?php

class Query {
    public function get_all_data($db, $table, $limit = "", $order = "", $sort = "") {
        $query = "SELECT * FROM ".$table;

        if ($limit != "") {
            $query .= " LIMIT ".$limit;
        }

        if ($order != "") {
            $query .= " ORDER BY ".$order;
        }

        if ($sort != "") {
            $query .= " ".$sort;
        }

        $all_data = $db->prepare($query);
        $all_data->execute();
        return $all_data->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_filter_data($db, $table, $param, $isi_param, $param2 = "", $isi_param2 = "") {
        $query = "SELECT * FROM ".$table." WHERE ".$param." = :".$param;

        if ($param2 != "" && $isi_param2 != "") {
            $query .= " AND ".$param2." = :".$param2;
        }

        $get_data = $db->prepare($query);
        $get_data->bindParam(":".$param, $isi_param);

        if ($param2 != "" && $isi_param2 != "") {
            $get_data->bindParam(":".$param2, $isi_param2);
        }

        $get_data->execute();
        return $get_data->fetch(PDO::FETCH_ASSOC);
    }

    public function insert_data($db, $table, $field, array $data) {
        $query = "INSERT INTO ".$table. " (".implode(",", $field).") VALUES ";

        if(!empty($data)) {

            for ($i = 0; $i < count($data); $i++) {
                $query .= "(";
                $isi_query = "";
                $ticket = $data[$i];

                foreach ($ticket as $key => $value) {
                    $isi_query .= "'".$value."',";
                }

                $isi_query = rtrim($isi_query, ",");
                $query .= $isi_query."),";
            }

            $query = rtrim($query, ",");
            $ins_data = $db->prepare($query);
            $ins_data->execute();
            return $db->lastInsertId();
        }
    }

    public function update_data($db, $table, $field_update1, $value_update1, $field_update2 = "", $value_update2 = "", $field_update3 = "", $value_update3 = "", $field_param1, $isi_param1, $field_param2 = "", $isi_param2 = "", $field_param3 = "", $isi_param3 = "") {
        $query = "UPDATE ".$table." SET ".$field_update1." = :".$field_update1;

        if ($field_update2 != "" && $value_update2 != "") {
            $query .= ", ".$field_update2." = :".$field_update2;
        }

        if ($field_update3 != "" && $value_update3 != "") {
            $query .= ", ".$field_update3." = :".$field_update3;
        }

        $query .= " WHERE ".$field_param1. " = :".$field_param1;

        if ($field_param2 != "" && $isi_param2 != "") {
            $query .= " AND ".$field_param2." = :".$field_param2;
        }

        if ($field_param3 != "" && $isi_param3 != "") {
            $query .= " AND ".$field_param3." = :".$field_param3;
        }

        $upd_data = $db->prepare($query);
        $upd_data->bindParam(":".$field_update1, $value_update1);

        if ($field_update2 != "" && $value_update2 != "") {
            $upd_data->bindParam(":".$field_update2, $value_update2);
        }

        if ($field_update3 != "" && $value_update3 != "") {
            $upd_data->bindParam(":".$field_update3, $value_update3);
        }

        $upd_data->bindParam(":".$field_param1, $isi_param1);

        if ($field_param2 != "" && $isi_param2 != "") {
            $upd_data->bindParam(":".$field_param2, $isi_param2);
        }

        if ($field_param3 != "" && $isi_param3 != "") {
            $upd_data->bindParam(":".$field_param3, $isi_param3);
        }

        return $upd_data->execute();
    }
}