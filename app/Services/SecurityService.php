<?php

namespace App\Services;

use App\Models\Proposta;

class SecurityService
{
    public function canAccessProposal(array $proposal, int $userId): bool
    {
        return (int) $proposal['owner_id'] === $userId || (int) $proposal['proposer_id'] === $userId;
    }

    public function canAccessChat(int $proposalId, int $userId): bool
    {
        $proposal = (new Proposta())->findById($proposalId);

        if (!$proposal || !in_array($proposal['status'], ['accepted', 'completed'], true)) {
            return false;
        }

        return $this->canAccessProposal($proposal, $userId);
    }
}
