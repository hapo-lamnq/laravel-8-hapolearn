<div class="container-fluid document-container">
    <div class="row">
        <div class="col-lg-12 title-documents">
            <p>Documents</p>
        </div>
        <div class="col-lg-12">
            @foreach ($documents as $item)
            <hr>
            <div class="row">
                <div class="col-lg-1 pr-0 type-file-container align-self-center">
                    <img class="pdf" @if ($item->type == 'pdf')
                    src="{{asset('images/pdf.png')}}" alt="pdf"
                    @elseif ($item->type == 'docx')
                    src="{{asset('images/docx.png')}}" alt="docx"
                    @elseif ($item->type == 'mp4')
                    src="{{asset('images/video.png')}}" alt="docx"
                    @endif
                    >
                </div>
                <div class="col-lg-6 pl-0 name-file-container align-self-center">
                    <a href="" class="title">{{$item->description}}</a>
                </div>
                <div class="col-lg-5 button-preview-container align-self-center">
                    <a href="{{url('view', $item->id)}}" target="_blank" rel="noopener norefer">Preview</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>