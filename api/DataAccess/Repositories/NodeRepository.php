<?php

namespace DataAccess\Repositories;


use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository {
    public function findByMissionAndDifficulty(int $missionId, string $difficulty): array {
        return $this->getEntityManager()->createQuery("SELECT n, note 
            FROM DataAccess\Models\Node n 
            LEFT JOIN DataAccess\Models\NodeNote note 
              WITH n.id = note.nodeId
            WHERE n.missionId = :missionId
              AND n.difficulty IN (:difficulty, 'ALL')
              AND n.approved = true 
            ORDER BY n.group ASC, n.name ASC")
            ->setParameter('missionId', $missionId)
            ->setParameter('difficulty', $difficulty)
            ->getResult();
    }
}