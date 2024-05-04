<?php

namespace App\Models\Methods;

use Illuminate\Database\Eloquent\Model;
use Multicaret\Acquaintances\Models\Friendship;
use Multicaret\Acquaintances\Status;

trait User
{
    /**
     * @param string $groupSlug
     * @param int $perPage Number
     * @param array $fields
     * @param string|null $status
     *
     * @return \Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getAllReceivedFriendships(
        string $groupSlug = '',
        int $perPage = 0,
        array $fields = ['*'],
        ?string $status = null
    ) {
        return $this->getOrPaginate($this->findFriendships($status, $groupSlug, 'recipient'), $perPage, $fields);
    }

    /**
     * @param string $groupSlug
     * @param int $perPage Number
     * @param array $fields
     * @param string|null $status
     *
     * @return \Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getAllSentFriendships(
        string $groupSlug = '',
        int $perPage = 0,
        array $fields = ['*'],
        ?string $status = null
    ) {
        return $this->getOrPaginate($this->findFriendships($status, $groupSlug, 'sender'), $perPage, $fields);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $recipient
     *
     * @return bool
     *
     * @package Multicaret\Acquaintances\Traits
     */
    public function hasBennDeniedBy(Model $recipient): bool
    {
        return Friendship::whereRecipient($recipient)->whereSender($this)->whereStatus(Status::DENIED)->exists();
    }
}
