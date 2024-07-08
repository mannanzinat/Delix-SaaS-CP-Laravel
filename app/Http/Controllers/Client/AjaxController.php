<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;
use App\Repositories\SuccessStoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    // public function organizations(Request $request, ClientRepository $repository): \Illuminate\Http\JsonResponse
    // {
    //     try {
    //         $organizations = $repository->activeOrganization([
    //             'q'        => $request->q,
    //             'paginate' => 20,
    //         ]);
    //         $options       = [];
    //         foreach ($organizations as $item) {
    //             $options[] = [
    //                 'text' => $item->org_name,
    //                 'id'   => $item->id,
    //             ];
    //         }
    //         return response()->json($options);
    //     }catch (\Exception $e) {
    //         return response()->json(['error' => __('something_went_wrong_please_try_again')]);
    //     }
    // }

    // public function instructor(Request $request, UserRepository $userRepository): \Illuminate\Http\JsonResponse
    // {
    //     try {
    //         $instructors = $userRepository->findUsers([
    //             'role_id'         => 2,
    //             'q'               => $request->q,
    //             'organization_id' => $request->organization_id,
    //         ]);
    //         $options     = [];
    //         foreach ($instructors as $item) {
    //             $options[] = [
    //                 'text' => $item->name,
    //                 'id'   => $item->id,
    //             ];
    //         }
    //         return response()->json($options);
    //     }catch (\Exception $e) {
    //         return response()->json(['error' => __('something_went_wrong_please_try_again')]);
    //     }
    // }

    public function user(Request $request, UserRepository $userRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $users   = $userRepository->findUsers([
                'q'       => $request->q,
                'take'    => 20,
                'role_id' => $request->role_id,
            ]);
            $options = [];
            foreach ($users as $item) {
                $options[] = [
                    'text' => $item->name,
                    'id'   => $item->id,
                ];
            }

            return response()->json($options);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function successStory(Request $request, SuccessStoryRepository $successStoryRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $stories = $successStoryRepository->activeStories([
                'q'    => $request->q,
                'lang' => $request->lang ?? app()->getLocale(),
            ]);

            $options = [];
            foreach ($stories as $item) {
                $options[] = [
                    'text' => $item->story_title ?: $item->title,
                    'id'   => $item->id,
                ];
            }

            return response()->json($options);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

    public function testimonial(Request $request, WebsiteTestimonialRepository $testimonialRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $testimonials = $testimonialRepository->activeTestimonials([
                'q'    => $request->q,
                'lang' => $request->lang ?? app()->getLocale(),
            ]);

            $options      = [];
            foreach ($testimonials as $item) {
                $options[] = [
                    'text' => $item->testimonial_name ?? $item->name,
                    'id'   => $item->id,
                ];
            }

            return response()->json($options);
        }catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }


}
