<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Http\Response\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    protected $user;
    protected $seller;

    public function __construct()
    {
        $this->user = User::find(Auth::id());
        $this->seller = $this->user->sellers()->first();
    }

    public function index()
    {
        $announcements = $this->seller->announcements()->get();
        if ($announcements->isEmpty()) {
            return JsonResponse::respondSuccess([]);
        }
        return JsonResponse::respondSuccess($announcements);
    }

    public function store(AnnouncementRequest $request)
    {
        $validated = $request->validated();
        $announcement = $this->seller->announcements()->create($validated);
        return JsonResponse::respondSuccess($announcement);
    }

    public function update(AnnouncementRequest $request, $id)
    {
        $validated = $request->validated();

        $announcement = $this->seller->announcements()->find($id);
        if (!$announcement) {
            return JsonResponse::respondErrorNotFound('Announcement not found');
        }

        $announcement->update($validated);
        return JsonResponse::respondSuccess($announcement);
    }

    public function delete($id)
    {
        $announcement = $this->seller->announcements()->find($id);
        if (!$announcement) {
            return JsonResponse::respondErrorNotFound('Announcement not found');
        }

        $announcement->delete();
        return JsonResponse::respondSuccess('Announcement deleted successfully');
    }
}
