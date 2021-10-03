<?php

class DonationConfig extends DonationAppModel
{
    public $useTable = "config";

    public function add($objectif, $email, $description, $color)
    {
        $this->create();
        $this->set(array(
            'objectif' => $objectif,
            'email' => $email,
            'description' => $description,
            'color' => $color
        ));
        $this->save();
    }

    public function edit($objectif, $email, $description, $color)
    {
        $find = $this->find('first');
        if(empty($find))
            return $this->add($objectif, $email, $description, $color);
        $objectif = $this->getDataSource()->value($objectif, 'int');
        $email = $this->getDataSource()->value($email, 'string');
        $description = $this->getDataSource()->value($description, 'string');
        $color = $this->getDataSource()->value($color, 'string');

        return $this->updateAll([
            'objectif' => $objectif,
            'email' => $email,
            'description' => $description,
            'color' => $color
        ], ['id' => 1]);
    }

    public function updateTotal($total)
    {
        $find = $this->find('first');
        if(empty($find))
            return $this->Lang->get('ERROR__BAD_REQUEST');
        $total = $this->getDataSource()->value($total, 'int');

        return $this->updateAll([
            'total' => $total
        ], ['id' => 1]);
    }
}