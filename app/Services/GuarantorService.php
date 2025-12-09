<?php

namespace App\Services;

use App\Models\Guarantor;

class GuarantorService
{
    public function store($guarantors, $customerId, $groupId)
    {
        foreach ($guarantors as $g) {
            if (empty($g['name']) && empty($g['cnic'])) {
                continue;
            }

            $guarantor = new Guarantor();
            $guarantor->group_id = $groupId;
            $guarantor->customer_id = $customerId;
            $guarantor->name = $g['name'] ?? null;
            $guarantor->father_name = $g['father_name'] ?? null;
            $guarantor->address = $g['address'] ?? null;
            $guarantor->phone = $g['phone'] ?? null;
            $guarantor->cnic = $g['cnic'] ?? null;
            $media = [];

            if (isset($g['cnic_front']) && $g['cnic_front']->isValid()) {
                $filename = time() . '_front_' . $g['cnic_front']->getClientOriginalName();
                $path = $g['cnic_front']->move(public_path('guarantors'), $filename);
                $media['front'] = 'guarantors/' . $filename;
            }

            if (isset($g['cnic_back']) && $g['cnic_back']->isValid()) {
                $filename = time() . '_back_' . $g['cnic_back']->getClientOriginalName();
                $path = $g['cnic_back']->move(public_path('guarantors'), $filename);
                $media['back'] = 'guarantors/' . $filename;
            }

            if (!empty($media)) {
                $guarantor->cnic_media = json_encode($media);
            }
            $guarantor->save();
        }
    }

    public static function getByCustomerId($customerId)
    {
        return Guarantor::where('customer_id', $customerId)->get();
    }
    public function update($guarantorsData, $guarantorsFiles)
    {
        foreach ($guarantorsData as $index => $g) {
            if (empty($g['id'])) continue;

            $guarantor = Guarantor::find($g['id']);
            if (!$guarantor) continue;

            $guarantor->name = $g['name'] ?? null;
            $guarantor->father_name = $g['father_name'] ?? null;
            $guarantor->address = $g['address'] ?? null;
            $guarantor->phone = $g['phone'] ?? null;
            $guarantor->cnic = $g['cnic'] ?? null;

            $media = json_decode($guarantor->cnic_media, true) ?? [];
            if (isset($guarantorsFiles[$index]['cnic_front'])) {
                $file = $guarantorsFiles[$index]['cnic_front'];
                $filename = time() . '_front_' . $file->getClientOriginalName();
                $file->move(public_path('guarantors'), $filename);
                $media['front'] = 'guarantors/' . $filename;
            }

            if (isset($guarantorsFiles[$index]['cnic_back'])) {
                $file = $guarantorsFiles[$index]['cnic_back'];
                $filename = time() . '_back_' . $file->getClientOriginalName();
                $file->move(public_path('guarantors'), $filename);
                $media['back'] = 'guarantors/' . $filename;
            }

            if (!empty($media)) {
                $guarantor->cnic_media = json_encode($media);
            }

            $guarantor->save();
        }
    }
}
