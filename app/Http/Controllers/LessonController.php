<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentUser;
use App\Models\Feedback;
use App\Models\Lesson;
use App\Models\ReplyReview;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class LessonController extends Controller
{
    public function index($id)
    {
        $lessons = Lesson::find($id);
        $lessons->users()->attach(Auth::id());
        $course = Lesson::courseOfLesson($id)->first();
        $otherCourses = Course::limit(5)->get();
        $tags = Course::tagsCourse($course->id)->get();
        $mentors = Course::mentorOfCourse($course->id)->get();
        $numberStudent = Course::where('courses.id', $course->id)->first();
        $documents = Lesson::documentsOfLesson($id)->get();
        $documentsLearned = Document::documentLearned($id)->get();
        $percentage = $documents->count() > 0 ? round($documentsLearned->count() / $documents->count() * 100) : 0;
        $reply = ReplyReview::all();

        return view('lessons.index', compact('lessons', 'course', 'otherCourses', 'tags', 'numberStudent', 'mentors', 'documents', 'documentsLearned', 'percentage', 'reply'));
    }

    public function search(Request $request, $id)
    {
        $course = Course::find($id);
        $tags = Course::tagsCourse($id)->get();
        $otherCourses = Course::showOtherCourses($course->id)->get();
        $mentors = Course::mentorOfCourse($id)->get();
        $lessons = Lesson::search($request->all())->paginate(config('constants.pagination_lessons'));
        $isJoined = UserCourse::joined($id)->first() ? true : false;
        $reviews  = Feedback::feedbacksOfCourse($course->id)->get();
        $totalRate  = Feedback::feedbacksOfCourse($course->id)->sum('rate');
        $avgRating = $reviews->count() > 0 ? round($totalRate / $reviews->count()) : 0;
        $totalDocuments = !isNull($lessons->first()) ? Lesson::documentsOfLesson($lessons->first()->id)->get() : null;

        if (Auth::check() && !isNull($lessons->first())) {
            $documentsLearned = Document::documentLearned($lessons->first()->id)->get();
        } else {
            $documentsLearned = 0;
        }
        if (Auth::check() && !isNull($totalDocuments)) {
            if ($documentsLearned->count() != 0 && $totalDocuments->count() != 0) {
                $learnedPart = $documentsLearned->count() / $totalDocuments->count();
            } else {
                $learnedPart = 0;
            }
        } else {
            $learnedPart = 0;
        }

        $keyword = $request->has('key_detail_course') ? request()->get('key_detail_course') : null;

        return view('courses.course_detail', compact('course', 'lessons', 'tags', 'otherCourses', 'mentors', 'keyword', 'isJoined', 'reviews', 'totalRate', 'avgRating', 'learnedPart', 'totalDocuments', 'documentsLearned'));
    }
}
