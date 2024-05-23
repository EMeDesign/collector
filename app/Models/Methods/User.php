<?php

namespace App\Models\Methods;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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
     * Get the paginator of the 'friend' model
     *
     * @param \Closure $closure
     * @param int $perPage
     * @param string $groupSlug
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFriendsPaginator(\Closure $closure, int $perPage, string $groupSlug = ''): LengthAwarePaginator
    {
        $friendships = $this->findFriendships(Status::ACCEPTED, $groupSlug)->get(['sender_id', 'recipient_id']);
        $recipients = $friendships->pluck('recipient_id')->all();
        $senders = $friendships->pluck('sender_id')->all();

        $friends = $this->where('id', '!=', $this->getKey())
            ->whereIn('id', array_merge($recipients, $senders));

        return tap($friends, $closure)->paginate($perPage);
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
