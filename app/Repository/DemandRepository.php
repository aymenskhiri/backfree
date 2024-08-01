<?php

namespace App\Repository;

class DemandRepository
{
    public function prepareDemandData($request): array
    {
        return [
            'status' => $request->input('status'),
            'approuval' => $request->input('approuval', 'On Hold'),
            'service_date' => $request->input('service_date'),
            'description' => $request->input('description'),
            'post_id' => $request->input('post_id'),
            'freelancer_id' => $request->input('freelancer_id'),
            'client_id' => $request->input('client_id'),
        ];
    }
}
