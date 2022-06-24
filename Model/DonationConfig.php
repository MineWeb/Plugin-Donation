<?php

class DonationConfig extends DonationAppModel
{
    public $useTable = "config";

    public function add($objectif, $email, $description, $color, $command, $serverid)
    {
        $this->create();
        $this->set([
            'objectif' => $objectif,
            'email' => $email,
            'description' => $description,
            'color' => $color,
            'command' => $command,
            'server_id' => $serverid
        ]);
        $this->save();
    }

    public function edit($objectif, $email, $description, $color, $command, $serverid)
    {
        $find = $this->find('first');
        if(empty($find))
            return $this->add($objectif, $email, $description, $color);
        $objectif = $this->getDataSource()->value($objectif, 'int');
        $email = $this->getDataSource()->value($email, 'string');
        $description = $this->getDataSource()->value($description, 'string');
        $color = $this->getDataSource()->value($color, 'string');
        $command = $this->getDataSource()->value($command, 'string');
        $serverid = $this->getDataSource()->value($serverid, 'int');

        return $this->updateAll([
            'objectif' => $objectif,
            'email' => $email,
            'description' => $description,
            'color' => $color,
            'command' => $command,
            'server_id' => $serverid
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
