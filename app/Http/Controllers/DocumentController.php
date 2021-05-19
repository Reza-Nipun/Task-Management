<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DocumentController extends Controller
{
    protected $doc_api_base_url = 'http://10.234.34.156/kbank/api/';
    protected $doc_api_base_url_token = 'base64:2KaPiyfhZjmv4jBupIucIwetufCu+N3a9K6jGHCc2E=';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $category_list = $this->getCategoryList();
        $categories = $category_list->object()->data;

        $applicability_list = $this->getApplicabilityList();
        $applicabilities = $applicability_list->object()->data;

        $document_type_list = $this->getDocumentTypeList();
        $document_types = $document_type_list->object()->data;

        $department_list = $this->getDepartmentList();
        $departments = $department_list->object()->data;

        return view('document_list', compact('categories','applicabilities','document_types','departments'));
    }

    public function getCategoryList(){
        return $category_list = Http::get($this->doc_api_base_url.'categories/'.$this->doc_api_base_url_token);
    }

    public function getApplicabilityList(){
        return $applicability_list = Http::get($this->doc_api_base_url.'applicability_list/'.$this->doc_api_base_url_token);
    }

    public function getDocumentTypeList(){
        return $document_type_list = Http::get($this->doc_api_base_url.'document_types/'.$this->doc_api_base_url_token);
    }

    public function getDepartmentList(){
        return $document_type_list = Http::get($this->doc_api_base_url.'departments/'.$this->doc_api_base_url_token);
    }

    public function documentFilter(Request $request){
        $subject = $request->subject;
        $category = $request->category;
        $applicability = $request->applicability;
        $document_type = $request->document_type;
        $department = $request->department;

        $document_list = Http::get($this->doc_api_base_url.'get_filtered_documents/'.$this->doc_api_base_url_token.'/'.$subject.'/'.$category.'/'.$applicability.'/'.$document_type.'/'.$department);
        $documents = $document_list->object()->data;

        $new_row = '';

        foreach ($documents AS $k => $d){
            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td class="text-center">'.$d->subject.'</td>';
            $new_row .= '<td class="text-center">'.$d->category_name.'</td>';
            $new_row .= '<td class="text-center">'.$d->applicability_name.'</td>';
            $new_row .= '<td class="text-center">'.$d->document_departments.'</td>';
            $new_row .= '<td class="text-center">'.$d->document_type_name.'</td>';
            $new_row .= '<td class="text-center">'.$d->reference_code.'</td>';
            $new_row .= '<td class="text-center">'.$d->max_version.'</td>';
            $new_row .= '<td class="text-center">'.$d->remarks.'</td>';
            $new_row .= '<td class="text-center">
                            <a class="btn btn-sm btn-primary" href="'.url($this->doc_api_base_url.'get_document_info_by_id_api/'.$this->doc_api_base_url_token.'/'.$d->max_id).'" target="_blank" title="VIEW">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>';
            $new_row .= '</tr>';
        }

        return $new_row;
    }
}