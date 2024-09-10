@extends('panel.layouts.master')
@section('title', 'فعالیت های اخیر')

@section('styles')
    <style>
        #stats i.fa, i.fab {
            font-size: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ $title }}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            @include('panel.partials.panel.activity-full', ['activities' => $activities, 'title' => $title])
        </div>
    </div>
@endsection
