<?php

return [
    'categories' => [
        'books' => 'Livros',
        'electronics' => 'Eletrônicos',
        'school_supplies' => 'Materiais Escolares',
        'clothing' => 'Roupas',
        'furniture' => 'Móveis',
        'services' => 'Serviços Acadêmicos',
        'other' => 'Outros',
    ],
    'conditions' => [
        'new' => 'Novo',
        'like_new' => 'Seminovo',
        'used_good' => 'Usado em bom estado',
        'used_regular' => 'Usado com marcas',
        'needs_repair' => 'Precisa de reparo',
    ],
    'anuncio_statuses' => [
        'active' => 'Ativo',
        'paused' => 'Pausado',
        'completed' => 'Concluído',
        'removed' => 'Removido',
    ],
    'proposal_statuses' => [
        'pending' => 'Pendente',
        'accepted' => 'Aceita',
        'rejected' => 'Recusada',
        'cancelled' => 'Cancelada',
        'completed' => 'Concluída',
    ],
    'report_reasons' => [
        'inappropriate_behavior' => 'Comportamento inadequado',
        'fraud_suspicion' => 'Suspeita de fraude',
        'offensive_content' => 'Conteúdo ofensivo',
        'fake_item' => 'Item falso',
        'spam' => 'Spam',
        'other' => 'Outro',
    ],
    'report_statuses' => [
        'pending' => 'Pendente',
        'reviewing' => 'Em análise',
        'resolved' => 'Resolvida',
        'archived' => 'Arquivada',
    ],
    'reputation_levels' => [
        'beginner' => 'Iniciante',
        'bronze' => 'Bronze',
        'silver' => 'Prata',
        'gold' => 'Ouro',
    ],
    'upload' => [
        'max_size' => (int) env('UPLOAD_MAX_SIZE', 2097152),
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp'],
    ],
];
