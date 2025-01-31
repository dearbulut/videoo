@extends("admin.admin_app")

@section("content")

  
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                 
                <div class="row">
                     <div class="col-sm-6">
                          <a href="{{ URL::to('admin/sports') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
                     </div>
                     @if(isset($video_info->id))
                     <div class="col-sm-6">
                        <a href="{{ URL::to('sports/'.$video_info->video_slug.'/'.$video_info->id) }}" target="_blank"><h4 class="header-title m-t-0 m-b-30 text-primary pull-right" style="font-size: 20px;">{{trans('words.preview')}} <i class="fa fa-eye"></i></h4> </a>
                     </div>
                     @endif
                   </div>

                
                 

                 {!! Form::open(array('url' => array('admin/sports/add_edit_video'),'class'=>'form-horizontal','name'=>'video_form','id'=>'video_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  
                  <input type="hidden" name="id" value="{{ isset($video_info->id) ? $video_info->id : null }}">

                  <div class="row">

                  <div class="col-md-6">
                    <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.sport_info')}}</h4> 

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.video_title')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="video_title" value="{{ isset($video_info->video_title) ? stripslashes($video_info->video_title) : null }}" class="form-control">
                    </div>
                  </div>                  
                  <div class="form-group row">
                    <label for="webSite" class="col-sm-12 col-form-label">{{trans('words.description')}}</label>
                    <div class="col-sm-12">
                      <div class="card-box pl-0 description_box">
            
                      <textarea id="elm1" name="video_description">{{ isset($video_info->video_description) ? stripslashes($video_info->video_description) : null }}</textarea>
                     
                    </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.video_access')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="video_access">                               
                                <option value="Paid" @if(isset($video_info->video_access) AND $video_info->video_access=='Paid') selected @endif>{{trans('words.paid')}}</option>
                                <option value="Free" @if(isset($video_info->video_access) AND $video_info->video_access=='Free') selected @endif>{{trans('words.free')}}</option>                            
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.sports_cat_text')}} *</label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="video_category">
                                <option value="">{{trans('words.select_category')}}</option>
                                @foreach($cat_list as $cat_data)
                                  <option value="{{$cat_data->id}}" @if(isset($video_info->id) && $cat_data->id==$video_info->sports_cat_id) selected @endif>{{$cat_data->category_name}}</option>
                                @endforeach
                            </select>
                      </div>
                  </div> 
                   
                  
                  <div class="form-group row">
                    <label class="control-label col-sm-3">{{trans('words.date')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group"> 
                        <input type="text" id="datepicker-autoclose" name="date" value="{{ isset($video_info->date) ? $video_info->date ? date('m/d/Y',$video_info->date) : '' : old('date') }}" class="form-control" placeholder="mm/dd/yyyy">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="ti-calendar"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.duration')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                      <input type="text" name="duration" value="{{ isset($video_info->duration) ? $video_info->duration : null }}" class="form-control" placeholder="1h 35m 54s">
                      <div class="input-group-append">
                            <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                        </div>
                    </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.status')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="status">                               
                                <option value="1" @if(isset($video_info->status) AND $video_info->status==1) selected @endif>{{trans('words.active')}}</option>
                                <option value="0" @if(isset($video_info->status) AND $video_info->status==0) selected @endif>{{trans('words.inactive')}}</option>                            
                            </select>
                      </div>
                  </div>

                  <hr/>
                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.seo')}}</h4>
                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_title')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="seo_title" id="seo_title" value="{{ isset($video_info->seo_title) ? stripslashes($video_info->seo_title) : old('seo_title') }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_desc')}}</label>
                    <div class="col-sm-8">                       
                      <textarea name="seo_description" id="seo_description" class="form-control">{{ isset($video_info->seo_description) ? stripslashes($video_info->seo_description) : old('seo_description') }}</textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_keyword')}}</label>
                    <div class="col-sm-8">                      
                      <textarea name="seo_keyword" id="seo_keyword" class="form-control">{{ isset($video_info->seo_keyword) ? stripslashes($video_info->seo_keyword) : old('seo_keyword') }}</textarea>
                      <small id="emailHelp" class="form-text text-muted">{{trans('words.seo_keyword_note')}}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                    <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.sport_poster_video')}}</h4>
                    
 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.video_poster')}}</label>
                    <div class="col-sm-8">                       
                      <div class="input-group">
                          <input type="text" name="video_image" id="video_image" value="{{ isset($video_info->video_image) ? $video_info->video_image : null }}" class="form-control" readonly>
                          <div class="input-group-append">                           
                            <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="video_image" data-preview="holder_thumb" data-inputid="video_image">Select</button>                        
                          </div>
                      </div>
                      <small id="emailHelp" class="form-text text-muted">({{trans('words.recommended_resolution')}} : 800x450)</small>
                      <div id="image_holder" style="margin-top:5px;max-height:100px;"></div>                     
                    </div>
                  </div>

                  @if(isset($video_info->video_image)) 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">
                      <img src="{{URL::to('/'.$video_info->video_image)}}" alt="video image" class="img-thumbnail" width="200"> 
                    </div>
                  </div>
                  @endif

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.video_upload_type')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="video_type" id="video_type">                               
                                <option value="Local" @if(isset($video_info->video_type) AND $video_info->video_type=="Local") selected @endif>Local</option>
                                <option value="URL" @if(isset($video_info->video_type) AND $video_info->video_type=="URL") selected @endif>URL</option>
                                <option value="HLS" @if(isset($video_info->video_type) AND $video_info->video_type=="HLS") selected @endif>HLS/m3u8 / MPEG-DASH / YouTube / Vimeo</option>
                                <option value="Embed" @if(isset($video_info->video_type) AND $video_info->video_type=="Embed") selected @endif>Embed Code</option>
                                
                                                             
                            </select>
                      </div>
                  </div>

                  <div class="form-group row">
                  <label class="col-sm-3 col-form-label">{{trans('words.video_quality')}}<small id="emailHelp" class="form-text text-muted">(For Local and URL)</small></label>
                    <div class="col-sm-8">
                      <div class="radio radio-success form-check-inline pl-2"  style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio1" value="1" name="video_quality" @if(isset($video_info->video_quality) && $video_info->video_quality==1) {{ 'checked' }} @endif>
                          <label for="inlineRadio1"> {{trans('words.active')}} </label>
                      </div>
                      <div class="radio form-check-inline" style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio2" value="0" name="video_quality" @if(isset($video_info->video_quality) && $video_info->video_quality==0) {{ 'checked' }} @endif {{ isset($video_info->id) ? '' : 'checked' }}>
                          <label for="inlineRadio2"> {{trans('words.inactive')}} </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group row" id="local_id" @if(isset($video_info->video_type) AND $video_info->video_type!="Local") style="display:none;" @endif>

                  <div class="col-sm-11">
                    <small id="emailHelp" class="form-text text-muted">(Supported : MP4)</small></label><br>
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} <small id="emailHelp" class="form-text text-muted">(Defualt Player File)</small></label>
                    <div class="col-sm-8 mb-3">
                       
                      <div class="input-group">
                        <input type="text" name="video_url_local" id="video_url_local" value="{{ isset($video_info->video_url) ? $video_info->video_url : null }}" class="form-control" readonly>
                        <div class="input-group-append">                           
                          <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="video_url_local" data-inputid="video_url_local">Select</button>                      
                        </div>
                      </div>
                     
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} 480P <small id="emailHelp" class="form-text text-muted"></small></label>
                    <div class="col-sm-8 mb-3">
                      <div class="input-group">

                        <input type="text" name="video_url_local_480" id="video_url_local_480" value="{{ isset($video_info->video_url_480) ? $video_info->video_url_480 : null }}" class="form-control" readonly>
                        <div class="input-group-append">                           
                          <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="video_url_local_480" data-inputid="video_url_local_480">Select</button>                      
                        </div>
                      </div>
                     
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} 720P <small id="emailHelp" class="form-text text-muted"></small></label>
                    <div class="col-sm-8 mb-3">
                      <div class="input-group">

                        <input type="text" name="video_url_local_720" id="video_url_local_720" value="{{ isset($video_info->video_url_720) ? $video_info->video_url_720 : null }}" class="form-control" readonly>
                        <div class="input-group-append">                           
                          <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="video_url_local_720" data-inputid="video_url_local_720">Select</button>                      
                        </div>
                      </div>
                     
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} 1080P <small id="emailHelp" class="form-text text-muted"></small></label>
                    <div class="col-sm-8 mb-3">
                      <div class="input-group">

                        <input type="text" name="video_url_local_1080" id="video_url_local_1080" value="{{ isset($video_info->video_url_1080) ? $video_info->video_url_1080 : null }}" class="form-control" readonly>
                        <div class="input-group-append">                           
                          <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="video_url_local_1080" data-inputid="video_url_local_1080">Select</button>                      
                        </div>
                      </div>
                     
                    </div>

                  </div>

                  <div class="form-group row" id="url_id" @if(isset($video_info->video_type) AND $video_info->video_type!="URL") style="display:none;" @endif @if(!isset($video_info->id)) style="display:none;" @endif>

                  <div class="col-sm-11">
                    <small id="emailHelp" class="form-text text-muted">(Supported : MP4 URL. If you are using external files then those files have to be <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS" target="_blank">CORS</a> enabled otherwise they will not work.)</small></label><br>
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} <small id="emailHelp" class="form-text text-muted">(Defualt Player File)</small></label>
                     <div class="col-sm-8 mb-3">
                      <input type="text" name="video_url" value="{{ isset($video_info->video_url) ? $video_info->video_url : null }}" class="form-control" placeholder="http://example.com/demo.mp4">
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} 480P<small id="emailHelp" class="form-text text-muted"></small></label>
                     <div class="col-sm-8 mb-3">
                      <input type="text" name="video_url_480" value="{{ isset($video_info->video_url_480) ? $video_info->video_url_480 : null }}" class="form-control" placeholder="http://example.com/demo480.mp4">
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} 720P<small id="emailHelp" class="form-text text-muted"></small></label>
                     <div class="col-sm-8 mb-3">
                      <input type="text" name="video_url_720" value="{{ isset($video_info->video_url_720) ? $video_info->video_url_720 : null }}" class="form-control" placeholder="http://example.com/demo720.mp4">
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} 1080P<small id="emailHelp" class="form-text text-muted"></small></label>
                     <div class="col-sm-8 mb-3">
                      <input type="text" name="video_url_1080" value="{{ isset($video_info->video_url_1080) ? $video_info->video_url_1080 : null }}" class="form-control" placeholder="http://example.com/demo1080.mp4">
                    </div>

                  </div>

                  <div class="form-group row" id="embed_id" @if(isset($video_info->video_type) AND $video_info->video_type!="Embed") style="display:none;" @endif @if(!isset($video_info->id)) style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.video_embed_code')}}</label>
                     <div class="col-sm-8 mb-3">
                       <textarea class="form-control" name="video_embed_code">{{ isset($video_info->video_url) ? $video_info->video_url : null }}</textarea>
                    </div>
                  </div>

                  <div class="form-group row" id="hls_id" @if(isset($video_info->video_type) AND $video_info->video_type!="HLS") style="display:none;" @endif @if(!isset($video_info->id)) style="display:none;" @endif>
                     
                    <div class="col-sm-11">
                    <small id="emailHelp" class="form-text text-muted">(Supported : MP4, YouTube, Vimeo, HLS / m3u8 URL. If you are using external files then those files have to be <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS" target="_blank">CORS</a> enabled otherwise they will not work.)</small></label><br>
                    </div>   

                    <label class="col-sm-3 col-form-label">{{trans('words.hls_streaming')}}</label>
                     <div class="col-sm-8 mb-3">
                       <input type="text" name="video_url_hls" value="{{ isset($video_info->video_url) ? $video_info->video_url : null }}" class="form-control" placeholder="http://example.com/test.m3u8">
                    </div>
                  </div>
                  <div class="form-group row" id="dash_id" @if(isset($video_info->video_type) AND  $video_info->video_type!="DASH") style="display:none;" @endif @if(!isset($video_info->id)) style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.mpeg_dash_streaming')}}</label>
                     <div class="col-sm-8">
                       <input type="text" name="video_url_dash" value="{{ isset($video_info->video_url) ? $video_info->video_url : null }}" class="form-control" placeholder="http://example.com/test.mpd">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.download')}}</label>
                    <div class="col-sm-8">
                      <div class="radio radio-success form-check-inline pl-2"  style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio3" value="1" name="download_enable" @if(isset($video_info->download_enable) && $video_info->download_enable==1) {{ 'checked' }} @endif>
                          <label for="inlineRadio3"> {{trans('words.active')}} </label>
                      </div>
                      <div class="radio form-check-inline" style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio4" value="0" name="download_enable" @if(isset($video_info->download_enable) && $video_info->download_enable==0) {{ 'checked' }} @endif {{ isset($video_info->id) ? '' : 'checked' }}>
                          <label for="inlineRadio4"> {{trans('words.inactive')}} </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.download_url')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="download_url" id="download_url" value="{{ isset($video_info->download_url) ? $video_info->download_url : old('download_url') }}" class="form-control">
                    </div>
                  </div>

                  <hr/>
                  <h4 class="m-t-0 m-b-15 header-title" style="font-size: 20px;">{{trans('words.subtitles')}}</h4>
                  <div class="col-sm-9 pl-0"> 
                    <small id="emailHelp" class="form-text text-muted">(Supported : .srt or .vtt files URL only. If you are using external files then those files have to be <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS" target="_blank">CORS</a> enabled otherwise they will not work.)</small>
                  </div> <br/>

                  <div class="form-group row">
                  <label class="col-sm-3 col-form-label">{{trans('words.subtitles')}}</label>
                    <div class="col-sm-8">
                      <div class="radio radio-success form-check-inline pl-2"  style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio5" value="1" name="subtitle_on_off" @if(isset($video_info->subtitle_on_off) && $video_info->subtitle_on_off==1) {{ 'checked' }} @endif>
                          <label for="inlineRadio5"> {{trans('words.active')}} </label>
                      </div>
                      <div class="radio form-check-inline" style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio6" value="0" name="subtitle_on_off" @if(isset($video_info->subtitle_on_off) && $video_info->subtitle_on_off==0) {{ 'checked' }} @endif {{ isset($video_info->id) ? '' : 'checked' }}>
                          <label for="inlineRadio6"> {{trans('words.inactive')}} </label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_language1')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_language1" id="subtitle_language1" value="{{ isset($video_info->subtitle_language1) ? $video_info->subtitle_language1 : old('subtitle_language') }}" placeholder="English" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_url1')}}
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_url1" id="subtitle_url1" value="{{ isset($video_info->subtitle_url1) ? $video_info->subtitle_url1 : old('subtitle_url1') }}" class="form-control" placeholder="http://example.com/demo.srt">
                       
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_language2')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_language2" id="subtitle_language2" value="{{ isset($video_info->subtitle_language2) ? $video_info->subtitle_language2 : old('subtitle_language2') }}" placeholder="French" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_url2')}}
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_url2" id="subtitle_url2" value="{{ isset($video_info->subtitle_url2) ? $video_info->subtitle_url2 : old('subtitle_url2') }}" class="form-control" placeholder="http://example.com/demo.srt">
                       
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_language3')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_language3" id="subtitle_language3" value="{{ isset($video_info->subtitle_language3) ? $video_info->subtitle_language3 : old('subtitle_language3') }}" placeholder="Spanish" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_url3')}}
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_url3" id="subtitle_url3" value="{{ isset($video_info->subtitle_url3) ? $video_info->subtitle_url3 : old('subtitle_url3') }}" class="form-control" placeholder="http://example.com/demo.srt">
                       
                    </div>
                  </div>

                    
                  <div class="form-group">
                    <div class="offset-sm-9 col-sm-9">
                      <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> {{trans('words.save')}} </button>                      
                    </div>
                  </div>  
                  
                </div>                
              </div>    
 
                  
                {!! Form::close() !!} 
              </div>
            </div>            
          </div>              
        </div>
      </div>
      @include("admin.copyright") 
    </div> 

 
<script type="text/javascript">
     
     
// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {

    //alert(requestingField);

    var elfinderUrl = "{{ URL::to('/') }}/";

    if(requestingField=="video_image")
    {
      var target_preview = $('#image_holder');
      target_preview.html('');
      target_preview.append(
              $('<img>').css('height', '5rem').attr('src', elfinderUrl + filePath.replace(/\\/g,"/"))
            );
      target_preview.trigger('change');
    }
 
    //$('#' + requestingField).val(filePath.split('\\').pop()).trigger('change'); //For only filename
    $('#' + requestingField).val(filePath.replace(/\\/g,"/")).trigger('change');
 
}
 
 </script>

<script type="text/javascript">
    
    @if(Session::has('flash_message'))     
 
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        /*didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }*/
      })

      Toast.fire({
        icon: 'success',
        title: '{{ Session::get('flash_message') }}'
      })     
     
  @endif

  @if (count($errors) > 0)
                  
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<p>@foreach ($errors->all() as $error) {{$error}}<br/> @endforeach</p>',
            showConfirmButton: true,
            confirmButtonColor: '#10c469',
            background:"#1a2234",
            color:"#fff"
           }) 
  @endif

  </script>

@endsection