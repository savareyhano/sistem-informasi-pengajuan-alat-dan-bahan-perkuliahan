<?php


namespace App\Http\Repository;


interface RepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $data
     * @return mixed
     */
    public function update($data);
}
