<?php

namespace app\service\main;

use app\dbConnect;
use app\repository\MainRepository;
use app\service\BaseService;
use app\service\observation\ObservationService;
use mysql_xdevapi\Exception;

class MainService extends BaseService
{
    private MainRepository $mainRepository;
    private dbConnect $dbConnect;
    private ObservationService $observationService;

    public function __construct()
    {
        $this->mainRepository = new MainRepository();
        $this->dbConnect = new dbConnect();
        $this->observationService = new ObservationService();
    }

    public function getTree(): array
    {
        return array_map('self::mb_ucfirst_arr', $this->mainRepository->getTree());
    }

    public function getRow(int $id): array
    {
        return array_map('self::mb_ucfirst_arr', $this->mainRepository->getRow($id));
    }

    public function getByName(string $name): array
    {
        return $this->mainRepository->getByName($name);
    }

    public function getByParent(int $id): array
    {
        return $this->mainRepository->getByParent($id);
    }
    public function insertRow(string $name, int $id): void
    {
        if (empty($this->mainRepository->getRow($id))) {
            echo "Non-existing Parent";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->mainRepository->insertRow($name, $id);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful insert";
    }

    public function deleteRow(int $id): void
    {
        if (empty($this->getRow($id))) {
            echo 'Попытка удаления несуществующей записи';
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->deleteRecursiveRow($id);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo 'Successful delete';
    }

    private function deleteRecursiveRow(int $id): void
    {
        $children = array_column($this->mainRepository->getByParent($id), 'ID');
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->deleteRecursiveRow($child);
            }
        }
        $this->observationService->deleteObservationByTreeId($id);
        $this->mainRepository->deleteRow($id);
    }

    private function treeLoopCheck(int $parentId, int $id): bool
    {
        $parent = $this->mainRepository->getRow($parentId);
        $result = true;
        if ($parentId == 0) {
            return true;
        } elseif (empty($this->mainRepository->getRow($id)) || empty($this->mainRepository->getRow($parentId))) {
            return false;
        }
        if (!empty($parent)) {
            foreach ($parent as $value) {
                $parentId = $value['Parent_ID'];
                if ($id == $parentId) {
                    $result = false;
                }
                if (!$this->treeLoopCheck($parentId, $id)) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    public function updateRow(?string $name, ?int $parentId, int $id): void
    {
        $this->dbConnect->beginTransaction();
        try {
            if (isset($parentId) && $parentId !== $id && $this->treeLoopCheck($parentId, $id)) {
                if (isset($name)) {
                    $this->mainRepository->updateBoth($name, $parentId, $id);
                    echo "Успешное изменение полей Name, Parent_ID";
                } else {
                    $this->mainRepository->updateParent($parentId, $id);
                    echo "Успешное изменение поля Parent_ID";
                }
            } elseif (isset($name) && empty($parentId)) {
                $this->mainRepository->updateName($name, $id);
            } else {
                echo "Ошибка!";
            }
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function displayTree(): void
    {
        $result = $this->getTree();
        $html = '<table border="1" width="300" style="border-collapse:collapse;">';
        $html .= '<tr>';
        foreach ($result[0] as $key => $value){
            $html .= '<th style="text-align:center;">' . htmlspecialchars($key) . '</th>';
        }
        $html .= '</tr>';
        foreach($result as $value){
            $html .= '<tr>';
            foreach($value as $value2){
                $html .= '<td style="text-align:center;">' . htmlspecialchars($value2) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        echo $html;
    }
}
