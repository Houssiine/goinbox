@extends('layouts.main_mailer')
@section('title', 'Edit Campaign')
<style>
/* ===== SHARED CAMPAIGN PAGE STYLES ===== */

.content {
    padding: 1rem;
}

/* Block placeholder */
#block_placeholder {
    margin-top: 10px;
}

/* Duallistbox - consistent height */
#bootstrap-duallistbox-nonselected-list_,
#bootstrap-duallistbox-selected-list_ {
    height: 200px;
    min-height: 150px;
    max-height: 300px;
}

/* Block header */
.block-header {
    margin-top: -20px;
}

/* Ace Editor border */
.ace_editor {
    border: 1px solid #d5dce1;
    border-radius: 5px;
}

/* Email test textarea */
textarea#email_test {
    height: 36px;
}

/* Placeholder rotate inputs - USE CLASS */
.placeholder-rotate-input {
    height: 48px;
}

/* Vade Results Popup */
.vade-results-popup {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.vade-html-container {
    text-align: left !important;
    padding: 0 !important;
}

.vade-results pre {
    background: white;
    color: #212529;
    padding: 10px;
    border-radius: 4px;
    font-family: 'Courier New', Consolas, monospace;
    font-size: 12px;
    line-height: 1.5;
}

</style>
@section('content')
    @include('mailer.campaigns.form.classic-banner')
    @if(! empty($stale_ips_removed) && $stale_ips_removed > 0)
        <div class="alert alert-warning alert-dismissible py-2 mb-2" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            <b>{{ $stale_ips_removed }}</b> previously-saved
            {{ Str::upper($campaign->mta) }} entr{{ $stale_ips_removed === 1 ? 'y was' : 'ies were' }}
            removed from the selection because their MTA no longer exists
            (likely after a RESET VMTAs / re-push). Click <b>SAVE / EDIT</b>
            to persist the cleaned selection.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    <div class="quick-nav" style="display:none">
        <a class="quick-nav-trigger" href="javascript:void(0)"><span></span></a>
        <ul>
            <li>
                <a href="javascript:void(0)" id="edit_campaign">
                    <span>SAVE/EDIT</span><i class="fas fa-save"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" id="test_campaign">
                    <span>TEST CAMPAIGN</span><i class="fas fa-eye"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" id="pmta_admin">
                    <span>PMTA ADMIN</span><i class="fas fa-desktop"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" id="vmta">
                    <span>VMTA</span><i class="fas fa-cog"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" id="pmta_errors">
                    <span>PMTA ERRORS</span><i class="fas fa-exclamation-triangle"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" id="isp_config">
                    <span>ISP CONFIG</span><i class="fas fa-file-code"></i>
                </a>
            </li>
        </ul>
        <span class="quick-nav-bg"></span>
    </div>
    <div class="quick-nav-overlay"></div>
<div class="col-sm-12">
        <div class="block block-rounded">
            <div class="block-content">
                <div class="d-flex align-items-center justify-content-center">
                            <div class="block-header">
            <h3 class="block-title">EDIT CAMPAIGN <span class="text-info">{{ $campaign->id }}</span></h3>
        </div>
                </div>
            </div>
        </div>
    </div>
					    <div class="col-sm-12">
        <div class="block block-rounded">
            <div class="block-content">
                <div class="d-flex flex-wrap">
                    <div class="flex-grow-1" style="width: 100%;">
                        <div class="form-group">
                                              @include('layouts.skeleton.tags')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-rounded" id="data">
        <div class="block-content">
			            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label for="from">
                            From: <i id="from_copy" class="fas fa-copy curs mr-2" title="copy from"></i>
                        </label>
                        <select id="from" class="form-control" multiple>
                            @foreach($assets['from'] as $from)
                                <option value="{{ $from->id }}">{{ $from->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label for="subject">
                            Subjects: <i id="subject_copy" class="fas fa-copy curs mr-2" title="copy subject"></i>
                        </label>
                        <select id="subject" class="form-control" multiple>
                            @foreach($assets['subjects'] as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label for="creative">Creatives:</label>
                        <select id="creative" class="form-control">
                            @foreach($assets['creatives'] as $creative)
                                <option value="{{ $creative->id }}">{{ $creative->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label for="html">Html:</label>
                        <select id="html" class="form-control">
                            <option value="" selected></option>
                            @foreach($assets['html'] as $creative)
                                <option data-src="{{ $creative->value }}" value="{{ $creative->id }}">{{ Str::limit($creative->value, 50) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
			<hr>
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                                                                <div class="form-group">
                                        <label for="domain">Redirect Domain:</label>
                                        <select id="domain" class="selectpicker form-control" data-allow-clear="true"
                                                title="Select Domain" data-live-search="true" data-size="6">
									@foreach(redirect_domains() as $i => $rd)
									<option value="{{ $rd['value'] }}" @selected($i === 0)>{{ $rd['label'] }}</option>
									@endforeach
                                        </select>
                                    </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="saved_emails">Saved Test Emails:</label>
                                <div class="input-group">
                                    <select id="saved_emails" class="form-control selectpicker"
                                            data-actions-box="true" data-live-search="true"
                                            data-selected-text-format="count > 1" data-allow-clear="true"
                                            data-size="5" title="Select Emails" multiple>
                                    </select>
                                    <div class="input-group-append">
                                        <button id="add_email" class="btn btn-success" title="Add Email">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="email_test">Email Test:</label>
                                <textarea id="email_test" rows="2" class="form-control"
                                  placeholder="Email Test">{{ implode("\n", $campaign->email_tests) }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4" style="margin-top: 26px;">
                            <div class="form-group  d-flex justify-content-center align-items-center">
                                                     <button class="btn btn-outline-primary ml-2" onclick="regularTest()">
                    <i class="fas fa-paper-plane mr-2"></i>NORMAL TEST
                </button>
                @if(config('settings.vade_enabled', false))
                <button type="button"
                id="vade_test_btn"
                class="btn btn-outline-warning ml-2">
                <i class="fas fa-shield-alt mr-2"></i>VADE TEST
</button>
                @endif
<button id="create_campaign_btn" class="btn btn-outline-success ml-2">
            <i class="fas fa-save mr-2"></i>SAVE/EDIT
        </button>
                            </div>
                        </div>
                    </div>
            <hr>
                              <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="saved_headers">Saved Headers:</label>
                                <div class="input-group">
                                    <select id="saved_headers" class="form-control selectpicker"
                                            data-live-search="true" title="Select Header">
                                        @foreach($headers as $header)
                                            <option value="{{ $header->id }}">{{ $header->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button id="save_header" class="btn btn-success" title="Save Header">
                                            <i class="fas fa-save"></i>
                                        </button>
                                        <button id="delete_header" class="btn btn-danger" title="Delete Header">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                           <div id="block_placeholder" class="block block-transparent block-mode-hidden">
      <div class="block-header" style="border-bottom: 0px solid !important;">
        <div class="block-title">PLACEHOLDERS</div>
        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle">
          <i class="si si-arrow-down"></i>
        </button>
        <div class="block-options">
          <div class="custom-control custom-switch custom-control-inline">
            <input type="checkbox" class="custom-control-input" id="placeholder_combination" name="combination">
            <label class="custom-control-label" for="placeholder_combination">
              COMBINATION
            </label>
          </div>
          <div class="custom-control custom-switch custom-control-inline">
            <input type="checkbox" class="custom-control-input" id="placeholder_all_combination" name="combination">
            <label class="custom-control-label" for="placeholder_all_combination">
              ALL COMBINATION
            </label>
          </div>
        </div>
      </div>
      <div class="block-content p-0">
        <div class="table-responsive">
          <table id="placeholders" class="table table-sm table-hover"></table>
        </div>
      </div>
    </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
					<hr>
<div class="row">
  <!-- Return Path (20% width) -->
  <div class="col-md-6">
    <div class="form-group">
      <label for="return_path">Return Path :</label>
<input id="return_path" type="text" class="form-control" placeholder="Return Path"
                               value="{{ $campaign->return_path }}">    </div>
  </div>

  <!-- Content Type (20% width) -->
  <div class="col-md-1">
    <div class="form-group">
      <label for="content_type">Content Type:</label>
      <select id="content_type" class="selectpicker form-control" title="select content type">
        <option value="text/html" selected>text/html</option>
        <option value="text/plain">text/plain</option>
        <option value="multipart/alternative">multipart/alternative</option>
      </select>
    </div>
  </div>

  <!-- Charset (20% width) -->
  <div class="col-md-1">
    <div class="form-group">
      <label for="charset">Charset:</label>
      <select id="charset" class="selectpicker form-control" title="select charset">
        <option value="UTF-8" selected>UTF-8</option>
        <option value="UTF-7">UTF-7</option>
        <option value="UTF-16">UTF-16</option>
        <option value="UTF-32">UTF-32</option>
        <option value="UTF-32BE">UTF-32BE</option>
        <option value="UTF-32LE">UTF-32LE</option>
        <option value="UTF-16BE">UTF-16BE</option>
        <option value="UTF-16LE">UTF-16LE</option>
        <option value="UTF7-IMAP">UTF7-IMAP</option>
        <option value="ASCII">ASCII</option>
        <option value="UCS-4">UCS-4</option>
        <option value="Windows-1252">Windows-1252</option>
        <option value="Windows-1254">Windows-1254</option>
        <option value="ISO-8859-1">ISO-8859-1</option>
        <option value="ISO-8859-2">ISO-8859-2</option>
        <option value="ISO-8859-3">ISO-8859-3</option>
        <option value="ISO-8859-4">ISO-8859-4</option>
        <option value="ISO-8859-5">ISO-8859-5</option>
        <option value="ISO-8859-6">ISO-8859-6</option>
        <option value="ISO-8859-7">ISO-8859-7</option>
        <option value="ISO-8859-8">ISO-8859-8</option>
        <option value="ISO-8859-9">ISO-8859-9</option>
        <option value="ISO-8859-10">ISO-8859-10</option>
        <option value="ISO-8859-13">ISO-8859-13</option>
        <option value="ISO-8859-14">ISO-8859-14</option>
        <option value="ISO-8859-15">ISO-8859-15</option>
        <option value="ISO-8859-16">ISO-8859-16</option>
      </select>
    </div>
  </div>

  <div class="col-md-2">
    <div class="form-group">
      <label for="transfer">Transfer Encoding:</label>
      <select id="transfer" class="selectpicker form-control" title="select transfer">
        <option value="7bit">7 BIT</option>
        <option value="8bit" selected >8 BIT</option>
        <option value="binary">BINARY</option>
        <option value="quoted-printable">QUOTED-PRINTABLE</option>
        <option value="base64">BASE64</option>
      </select>
    </div>
  </div>

  <div class="col-md-2">
    <div class="form-group">
      <label for="negative">Negative:</label>
      <select id="negative" class="selectpicker form-control" data-allow-clear="true" title="Select Negative" data-live-search="true" data-size="6">
        @foreach($negatives as $negative)
          <option value="{{$negative->id}}">{{ $negative->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    @php
                        $editHeaderTexts = array_map(
                            fn ($blob) => base64_decode($blob),
                            $campaign->normalizedHeaders()
                        );
                        if (count($editHeaderTexts) === 0) {
                            $editHeaderTexts = [''];
                        }
                    @endphp
                    @include('mailer.campaigns.partials.header_tabs', [
                        'aceId' => 'campaign_header',
                        'headerRotateEvery' => $campaign->header_rotate_every ?? 1,
                        'initialHeaders' => $editHeaderTexts,
                    ])
                    @include('layouts.skeleton.ace_encode_toolbar')
                    @include('layouts.skeleton.vmta_quick_modal')
                    @include('layouts.skeleton.fetch_email_modal')
                    @include('layouts.skeleton.fraction_ip')
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="campaign_body">Body:
                            <i id="preview" title="Preview" class="fas fa-eye curs mr-2"></i>
                            <i id="generate_links" title="Generate Links" class="fas fa-link curs mr-2"></i>
                            <i id="shortlink" title="Shortlink" class="fas fa-unlink curs mr-2"></i>
                            <div class="custom-control custom-switch custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="body_map"
                                       name="body_map">
                                <label class="custom-control-label" for="body_map">MAP BODY</label>
                            </div>
                        </label>
                        <textarea id="campaign_body" class="form-control" rows="28" placeholder="BODY"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="option">Option</label>
                                <select id="option" class="selectpicker form-control"
                                        title="Select Option" data-live-search="true">
                                    <option value="ip" selected>IP</option>
                                    <option value="vmta">VMTA</option>
                                    <option value="smtp">SMTP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="type">Type Ip:</label>
                                <select id="type" class="selectpicker form-control"
                                        title="Select Type" data-live-search="true">
                                    <option value="all">ALL</option>
                                    <option value="ipv4" selected>IPV4</option>
                                    <option value="ipv6">IPV6</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="servers">Servers:
                                    <i id="reloadServers" title="Reload Servers" class="fas fa-sync curs mr-2"></i>
                                    <i id="searchServers" title="Search Servers" class="fas fa-search curs mr-2"></i>
                                </label>
                                <select id="servers" class="selectpicker form-control" title="Select Servers"
                                        data-live-search="true"
                                        data-actions-box="true"
                                        data-selected-text-format="count > 5"
                                        data-allow-clear="true"
                                        data-size="6" multiple>
                                    @foreach($servers as $server)
                                        <option value="{{$server->id}}">
                                            {{ $server->name }}{{ $server->id }} | {{ $server->ip }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select id="ips" class="form-control" multiple></select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="start_from">
                                    Start From:
                                    <span class="text-info">(TOTAL : {{ $campaign->send_count }})</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="start_from" placeholder="Start From"
                                           value="{{ $campaign->send_progress }}" min="0"
                                           max="{{ $campaign->send_count }}">
                                    <div class="input-group-append">
                                        <button id="change" class="btn btn-info">CHANGE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="send_type">Send Type:</label>
                                <select id="send_type" class="form-control" title="Send Type">
                                    <option value="delay" selected>DELAY</option>
                                    <option value="delayp">DELAYP (parallel)</option>
                                    <option value="delayps">DELAYPS (parallel)</option>
                                    <option value="hs">HS</option>
                                    <option value="hsp">HSP (parallel)</option>
                                    <option value="hsps">HSPS (parallel by server)</option>
                                    <option value="pickup">PICKUP (parallel)</option>
                                    <option value="pickups">PICKUPS (parallel by server)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total_send">Total Send:</label>
                                <input id="total_send" type="number" class="form-control" placeholder="Total Send"
                                       value="{{ $campaign->send_parameters['total_send'] ?? 1 }}"/>
                            </div>
                            <div class="form-group">
                                <label for="sleep_email">Sleep Btw Email: (MS)</label>
                                <input id="sleep_email" type="number" class="form-control" placeholder="Sleep Email"
                                       value="{{ $campaign->send_parameters['sleep_email'] ?? 0 }}"/>
                                <small id="estimate_speed"></small>
                            </div>
                            <div class="form-group">
                                <label for="seed_every">Test After:</label>
                                <input id="seed_every" type="number" class="form-control" placeholder="Test After"
                                       value="{{ $campaign->send_parameters['seed_every'] ?? 0 }}"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="duplicate_data">Duplicate Data:</label>
                                <input id="duplicate_data" type="number" class="form-control" placeholder="Duplicate Data"
                                       value="{{ $campaign->duplicate_data ?? 1 }}" min="1" />
                            </div>
                            <div class="form-group">
                                <label for="fraction_ip">Fraction Ip</label>
                                <input id="fraction_ip" type="number" class="form-control" placeholder="Fraction Ip"
                                       value="{{ $campaign->send_parameters['fraction_ip'] ?? 1 }}"/>
                            </div>
                            <div class="form-group">
                                <label for="sleep_ip">Sleep Btw Ip: (MS)</label>
                                <input id="sleep_ip" type="number" class="form-control" placeholder="Sleep Btw Ip"
                                       value="{{ $campaign->send_parameters['sleep_ip'] ?? 0 }}"/>
                            </div>
                            <div class="form-group">
                                <label for="bounce_limit">Bounce Limit:</label>
                                <input id="bounce_limit" type="number" class="form-control" placeholder="Bounce Limit"
                                       value="{{ $campaign->bounce_limit }}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="boxes_selected">Boxes Selected:</label>
                        <div class="d-flex">
                            <button id="fetchBoxes" class="btn btn-sm btn-light mr-2" tabindex="0" title="FETCH BOXES">
                                            <span>
                                                <i class="fas fa-satellite-dish"></i>
                                            </span>
                            </button>
                            <select id="boxes_selected" class="form-control selectpicker"
                                    title="boxes Selected" data-live-search="true"
                                    data-actions-box="true" multiple
                                    data-selected-text-format="count > 5"
                                    data-size="6" data-allow-clear="true">
                                @foreach($boxes as $box)
                                    <option value="{{$box->id}}">{{$box->subject}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="boxes_rotate_every">Rotate Every Ip:</label>
                        <input id="boxes_rotate_every" class="form-control" placeholder="Rotate" value="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('layouts.skeleton.duallistbox')
@include('layouts.skeleton.select2')
@include('layouts.skeleton.ace')
@include('layouts.skeleton.contextmenu')
@include('layouts.skeleton.append_grid')
@section('js_after')
    <script>
        let unsubscribe = @json($assets['unsubscribe']);
        const bodyZero = "<html>\n" +
            "<body>\n" +
            "<center>\n" +
            "<a href=\"http://[domain]/read/[rndun_12]/[token]/[rndln_8]\">\n" +
            "<font size=\"4\">TTILE</font><br>\n" +
            "<img src=\"http://[domain]/[imgr]\" />\n" +
            "</a><br>\n" +
            "<a href=\"http://[domain]/opt/[rnda_14]/[token]/[rndn_5]\">\n" +
            "<img src=\"http://[domain]/[imgu]\" />\n" +
            "</a><br>\n" +
            "<img src=\"http://[domain]/img/[rndu_10]/[token]/[rnda_6]\" />\n" +
            "</center>\n" +
            "</body>\n" +
            "</html>\n";

        const bodyMapZero = "<html>\n" +
            "<body>\n" +
            "<center>\n" +
            "<a href=\"http://[domain]/read/[rndun_12]/[token]/[rndln_8]\">\n" +
            "<h2 style=\"font-size:35px; color:#324ab2\">TITLE</h2>\n" +
            "</a>\n" +
            "<br/>\n" +
            "<img src=\"http://[domain]/[imgr]\" usemap=\"#r[ide]\" style=\"width: [imgr_width]px; height: [imgr_height]px\" />\n" +
            "<br/>\n" +
            "<map name=\"r[ide]\">\n" +
            "<area shape=\"rect\" coords=\"0,0,2000,2000\" target=\"_blank\" href=\"http://[domain]/read/[rndun_12]/[token]/[rndln_8]\"/>\n" +
            "</map>\n" +
            "<img src=\"http://[domain]/[imgu]\" usemap=\"#u[ide]\" style=\"width: [imgu_width]px; height: [imgu_height]px\"/>\n" +
            "<br/>\n" +
            "<map name=\"u[ide]\">\n" +
            "<area shape=\"rect\" coords=\"0,0,2000,2000\" target=\"_blank\" href=\"http://[domain]/opt/[rnda_14]/[token]/[rndn_5]\"/>\n" +
            "</map>\n" +
            "<img src=\"http://[domain]/img/[rndu_10]/[token]/[rnda_6]\">\n" +
            "</center>\n" +
            "</body>\n" +
            "</html>\n";

        $("#campaign_body").val(b64decode('{{ $campaign->body }}'));

        $("#domain").val('{{ $campaign->domain }}');
        $("#negative").val('{{ $campaign->negative_id }}');

        $("#from").val(@json($campaign->from)).trigger('change.select2');
        $("#subject").val(@json($campaign->subjects)).trigger('change.select2');
        $("#creative").val('{{ $campaign->creative_id }}').trigger('change.select2');

        $("#content_type").val('{{ $campaign->content_type }}').selectpicker('refresh');
        $("#charset").val('{{ $campaign->charset }}').selectpicker('refresh');
        $("#transfer").val('{{ $campaign->transfer }}').selectpicker('refresh');

        const option = "{{ $campaign->mta }}";
        $("#option").val(option).selectpicker('refresh');
        const type = "{{ $campaign->type }}";
        $("#type").val(type).selectpicker('refresh');
        $("#servers").val(@json($campaign->servers)).selectpicker('refresh');
        $("#send_type").val("{{ $campaign->send_type }}");



        $("#boxes_selected").val(@json($campaign->boxes_selected));
        $("#boxes_selected").selectpicker('refresh');
        $("#boxes_rotate_every").val("{{ $campaign->boxes_rotate_every }}");

        editorHeader = ace.edit("campaign_header");
        editorHeader.setTheme("ace/theme/github");
        editorHeader.session.setMode("ace/mode/text");
        editorHeader.setAutoScrollEditorIntoView(false);
        editorHeader.setShowPrintMargin(false);
        editorHeader.$blockScrolling = Infinity;
        editorHeader.setFontSize(13);
        editorHeader.setOptions({
            minLines: 30,
            maxLines: 30,
            enableBasicAutocompletion: true,
            enableLiveAutocompletion: true,
            indentedSoftWrap: false,
            wrap: false,
            showGutter: false,
        });
        initAceEncodeToolbar(editorHeader);
        campaignHeaderTabs.init(editorHeader);

        $("#shuffle").on('click', function (e) {
            // random
            Array.prototype.shuffle = function () {
                let x = this.length;
                if (x === 0) return false;
                let y = 0;
                let temp_x = "";
                let temp_y = "";
                while (--x) {
                    y = Math.floor(Math.random() * (x + 1));
                    temp_x = this[x];
                    temp_y = this[y];
                    this[x] = temp_y;
                    this[y] = temp_x;
                }
                return this;
            };

            let header = editorHeader.session.getValue();
            header = header.replace(/\r\n/g, "\n");
            // Group folded headers: a line starting with space/tab is a
            // continuation of the header above it, so keep them together.
            let groups = [];
            header.split("\n").forEach(function (line) {
                if (/^[ \t]/.test(line) && groups.length) {
                    groups[groups.length - 1] += "\n" + line;
                } else {
                    groups.push(line);
                }
            });
            header = groups.shuffle().join("\n");
            editorHeader.session.setValue(header);
        });

        $("#encode").on('click', function (e) {
            e.preventDefault();
            const link = "{{ route('mailer.tools.encoding.popup') }}"
            const iframe = `<iframe src="${link}" width="100%" scrolling="no" frameborder="0"></iframe>`
            bootbox.dialog({
                scrollable: true,
                closeButton: true,
                size: "lg",
                message: iframe,
                onEscape: true,
                centerVertical: true
            }).on('shown.bs.modal', function () {
                iFrameResize({}, 'iframe');
            }).on('hidden.bs.modal', function () {
                $("iframe").remove();
            });
        });

        $("#shortlink").on('click', function (e) {
            e.preventDefault();
            const link = "{{ route('mailer.tools.shortlinks.popup') }}"
            const iframe = `<iframe src="${link}" width="100%" scrolling="no" frameborder="0"></iframe>`
            bootbox.dialog({
                scrollable: true,
                closeButton: true,
                size: "lg",
                message: iframe,
                onEscape: true,
                centerVertical: true
            }).on('shown.bs.modal', function () {
                iFrameResize({}, 'iframe');
            }).on('hidden.bs.modal', function () {
                $("iframe").remove();
            });
        });

        editorBody = ace.edit("campaign_body");
        editorBody.setTheme("ace/theme/github");
        editorBody.session.setMode("ace/mode/html");
        editorBody.setAutoScrollEditorIntoView(false);
        editorBody.setShowPrintMargin(false);
        editorBody.$blockScrolling = Infinity;
        editorBody.setFontSize(13);
        editorBody.setOptions({
            minLines: 30,
            maxLines: 30,
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true,
            indentedSoftWrap: false,
            wrap: false,
            showGutter: false,
        });

        if (typeof initEmailFetcher === 'function') {
            initEmailFetcher(editorHeader, editorBody);
        }

        loadEmailTests("#saved_emails");
        $("#add_email").on("click", function (e) {
            e.preventDefault();
            const link = "{{ route('mailer.email-tests.index') }}"
            const iframe = `<iframe src="${link}" width="100%" scrolling="no" frameborder="0"></iframe>`
            bootbox.dialog({
                scrollable: true,
                closeButton: true,
                size: "lg",
                message: iframe,
                onEscape: true,
                centerVertical: true
            }).on('shown.bs.modal', function () {
                iFrameResize({}, 'iframe');
            }).on('hidden.bs.modal', function () {
                $("iframe").remove();
                loadEmailTests("#saved_emails")
            })
        });

        $("#saved_emails").on("change", function (e) {
            e.preventDefault();
            $("#email_test").val($(this).val().join("\n"));
        });

        const ips = $('#ips').bootstrapDualListbox({
            nonSelectedListLabel: 'Ips',
            selectedListLabel: 'Selected Ips',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            selectorMinimalHeight: 330
        });

        const setIps = function () {
            ips.empty();
            $.each(@json($campaign->ips), (i, v) => {
                const data = v.split(',');
                const domain_id = (option === 'vmta' || option === 'smtp') ? 0 : data[3];
                const ipId = (option === 'vmta' || option === 'smtp') ? data[2] : data[5];

                const username = option === 'smtp' ? `,${data[6]}` : (option === 'vmta' ? ',' : '');
                const vmtaDns = (option === 'vmta' || option === 'smtp') ? `,${data[7] || data[1]}` : '';
                const val = `${data[0]},${data[1]},${ipId},${domain_id},${data[4]},${data[5]}${username}${vmtaDns}`;
                const displayDns = (option === 'vmta' || option === 'smtp') ? (data[7] || data[1]) : data[1];
                const text = `${data[0]},${displayDns}${username}`;

                $('<option/>')
                    .val(val)
                    .text(text)
                    .attr('selected', 'selected')
                    .appendTo(ips)
            });
            ips.bootstrapDualListbox('refresh', true);
        }();

        $('input[name=combination]').on('change', function() {
            $('input[name=combination]').not(this).prop('checked', false);
        });

        let placeholderData = [[]]
        @if(count($campaign->placeholders) > 0)
            placeholderData = @json($campaign->placeholders).map(p => {
            return {
                data: p.data?.join("\n") || "",
                rotate_every: p.rotate_every || "1",
            }
        });
        @endif

        const editorPlaceHolders = new AppendGrid({
            element: "placeholders",
            uiFramework: "bootstrap4",
            iconFramework: "fontawesome5",
            columns: [
                {
                    name: "data",
                    display: "PLACEHOLDER",
                    type: 'textarea',
                    ctrlAttr: {
                        "rows": 2
                    },
                },
                {
                    name: "rotate_every",
                    display: "ROTATE EVERY",
                    type: 'number',
                    value: 1,
                    emptyCriteria: function (value) {
                        // Empty or value equals to 0 will consider as empty.
                        const numeric = parseFloat(value);
                        return !(!isNaN(numeric) && numeric > 0);
                    }
                }
            ],
            hideButtons: {
                insert: true,
                moveUp: true,
                moveDown: true,
                rowEmpty: true,
            },
            iconParams: {
                icons: {
                    remove: "fa fa-times",
                    append: "fa fa-plus",
                    removeLast: "fa fa-minus",
                }
            },
            sectionClasses: {
                table: "table-sm",
                control: "form-control-sm",
                buttonGroup: "btn-group-sm"
            },
            beforeRowRemove: App.rowRemoveConfirm(() => editorPlaceHolders),
            initData: placeholderData,
        });

// Function to update placeholder count in the header
function updatePlaceholderCount() {
    const placeholderRows = editorPlaceHolders.getAllValue();

    // Count non-empty placeholders (those with data and rotate_every > 0)
    const activeCount = placeholderRows.filter(p => {
        const hasData = p.data && p.data.trim().length > 0;
        const hasRotateEvery = p.rotate_every && parseInt(p.rotate_every) > 0;
        return hasData && hasRotateEvery;
    }).length;

    // Update the block title
    const blockTitle = document.querySelector('#block_placeholder .block-title');
    if (blockTitle) {
        if (activeCount > 0) {
            blockTitle.innerHTML = `PLACEHOLDERS <span style="color: red;"><b>(${activeCount} Active)</b></span>`;
        } else {
            blockTitle.textContent = 'PLACEHOLDERS';
        }
    }
}
// Hook into AppendGrid events to update count when user types or changes values
$(document).on('input change keyup paste', '#placeholders input, #placeholders textarea', function() {
    // Delay the count update to allow the grid to process the change
    setTimeout(updatePlaceholderCount, 200);
});

// Hook into button clicks (add/remove rows) with more specific targeting
$(document).on('click', '#placeholders .btn-group button, #placeholders tfoot button', function() {
    // Longer delay for add/remove operations
    setTimeout(updatePlaceholderCount, 300);
});

// Also listen for any changes in the entire placeholders table
$(document).on('DOMNodeInserted DOMNodeRemoved', '#placeholders', function() {
    setTimeout(updatePlaceholderCount, 300);
});

// Listen for AppendGrid specific events if available
$(document).on('appendGrid.afterRowAppended appendGrid.afterRowRemoved', '#placeholders', function() {
    setTimeout(updatePlaceholderCount, 200);
});

// Initial count update after everything is loaded
setTimeout(updatePlaceholderCount, 500);

        $("#from").select2({
            allowClear: true,
            placeholder: "Select a from",
            multi: true,
            closeOnSelect: false,
        });

        $("#subject").select2({
            allowClear: true,
            placeholder: "Select subjects",
            multi: true,
            closeOnSelect: false,
        });

        $("#creative").select2({
            allowClear: true,
            placeholder: "Select Creative",
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }
                const baseUrl = "{{ asset('/o/') }}";
                const image = baseUrl + "/" + state.text;
                return $(
                    `<div data-img="${image}"><img src="${image}" alt="${state.text}" width="150px"/>${state.text}</div>`
                );
            }
        }).on("select2:close", function (e) {
            $(".preview_creative").remove();
        });

        $("#html").select2({
            allowClear: true,
            placeholder: "Select html",
        });

        $(document).on({
            mouseenter: function (e) {
                const image = $(this).find('div').data('img');
                if (image) {
                    $("body").append(
                        "<p class='preview_creative'>" +
                        `<img style='width: 300px;' src="${image}" alt="" />` +
                        "</p>"
                    );
                    $(".preview_creative")
                        .css("top", (e.pageY - 400) + "px")
                        .css("left", (e.pageX - 300) + "px")
                        .css("position", "absolute")
                        .css("z-index", "9999")
                }
            },
            mousemove: function (e) {
                $(".preview_creative")
                    .css("top", (e.pageY - 400) + "px")
                    .css("left", (e.pageX - 300) + "px")
            },
            mouseleave: function (e) {
                $(".preview_creative").remove();
            }
        }, '.select2-results__options li');

        $("#from").on('change', function (e) {
            let header = editorHeader.session.getValue();
            const selected = $(this).find("option:selected");
            const values = selected.map(function () {
                return $(this).text();
            }).get();
            let replace = "From: [from] <";
            if (values.length === 1) {
                const from = values[0];
                replace = "From: " + from + " <";
            }
            header = header.replace(/from.*:.*</gi, replace);
            editorHeader.session.setValue(header)
        });

        $("#subject").on('change', function (e) {
            let header = editorHeader.session.getValue();
            const selected = $(this).find("option:selected");
            const values = selected.map(function () {
                return $(this).text();
            }).get();
            let replace = "Subject: [subject]";
            if (values.length === 1) {
                const subject = values[0];
                replace = "Subject: " + subject;
            }
            header = header.replace(/subject.*:.*/gi, replace);
            editorHeader.session.setValue(header)
        });

        $("#creative").on('change', function () {
            const imgr = $("#creative option:selected").text();
            const domain = $("#domain").val() || '[domain]';
            const body = editorBody.session.getValue();
            const result = body.replace(/src="[^"]*"/i, `src="http://${domain}/${imgr}"`);
            editorBody.session.setValue(result);
        });

        $("#html").on('change', function () {
            const src = $(this).find("option:selected").data('src');
            const domain = $("#domain").val() || '[domain]';
            const result = src.replaceAll('[domain]', domain);
            editorBody.session.setValue(result);
        });

        $("#from_copy").on('click', function (e) {
            e.preventDefault();
            const from = $("#from").find("option:selected").map(function () {
                return $(this).text();
            }).get();
            if (from.length) {
                window.copyToClipboard(from.join("\n"));
                alert('copied');
            }
        });

        $("#subject_copy").on('click', function (e) {
            e.preventDefault();
            const subjects = $("#subject").find("option:selected").map(function () {
                return $(this).text();
            }).get();
            if (subjects.length) {
                window.copyToClipboard(subjects.join("\n"));
                alert('copied');
            }
        });

        $("#domain").on('change', function () {
            const domain = $(this).val() || '[domain]';
            const body = editorBody.session.getValue();
            editorBody.session.setValue(body.replace(/(https?:\/\/)(.*?)\//g, "http://" + domain + '/'));
        });

        $("#preview").on("click", function (e) {
            e.preventDefault();

            const body = editorBody.session.getValue();
            if (body) {
                bootbox.dialog({
                    scrollable: true,
                    closeButton: true,
                    size: "lg",
                    message: $("<iframe />").attr({
                        srcdoc: body,
                        width: "100%",
                        height: "700px",
                        scrolling: "auto",
                        frameborder: "0"
                    }),
                    onEscape: true,
                    centerVertical: true
                })
            }
        });

        $("#body_map").on('change', async function () {
            const getMeta = (image) => {
                return new Promise(function (resolve, reject) {
                    if(!image) {
                        resolve([0,0]);
                    }
                    const link = "{{ asset('/o') }}" + "/" + image;
                    $("<img/>").attr('src', link)
                        .on('load', function () {
                            resolve([this.width, this.height]);
                        });
                });
            };

            const creative = $("#creative option:selected").text();
            if (!creative) {
                $(this).prop('checked', false);
                alert("Please select creative");
                return;
            }
            const domain = $("#domain").val() || '[domain]';

            let body = bodyZero
                .replace(/\[domain\]/gi, domain)
                .replace('[imgr]', creative || 'NA')
                .replace('[imgu]', unsubscribe?.value || 'NA');

            if (this.checked) {
                body = bodyMapZero
                    .replace(/\[domain\]/gi, domain)
                    .replace('[imgr]', creative || 'NA')
                    .replace('[imgu]', unsubscribe?.value || 'NA');

                const [wr, hr] = await getMeta(creative);
                body = body.replace('[imgr_width]', wr).replace('[imgr_height]', hr);
                const [wu, hu] = await getMeta(unsubscribe?.value);
                body = body.replace('[imgu_width]', wu).replace('[imgu_height]', hu);
            }
            editorBody.session.setValue(body);
        });

        $("#saved_headers").on("change", function (e) {
            const id = $(this).val();
            if (id) {
                $.ajax({
                    url: "{{ route('mailer.headers.header', ':id') }}".replace(':id', id),
                    type: 'get',
                    dataType: "json"
                }).done((res) => {
                    editorHeader.session.setValue(b64decode(res.data));
                })
            }
        });

        $("#save_header").on("click", async function (e) {
            e.preventDefault();
            const btn = this;
            const header = editorHeader.session.getValue();
            const {value: name} = await swal.fire({
                title: 'Enter Header Name',
                input: 'text'
            });
            if (header && name) {
                const html = $(btn).html()
                $.ajax({
                    url: "{{ route('mailer.headers.store') }}",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        name,
                        text: b64encode(header),
                    },
                    beforeSend: () => {
                        $(btn).attr('disabled', true).html(spinner())
                    }
                }).done(function (res) {
                    if (res.status) {
                        swal.fire({
                            title: 'Success',
                            text: 'Header Saved',
                            icon: 'success'
                        });
                    }
                    const headers = $("#saved_headers");
                    headers.html("");
                    $.each(res.headers, function (i, v) {
                        const option = $("<option></option>", {
                            "value": v.id,
                            "text": v.name
                        });
                        headers.append(option);
                    });
                    headers.selectpicker('refresh');
                }).always(function () {
                    $(btn).attr('disabled', false).html(html)
                })
            }
        });

        $("#delete_header").on("click", function (e) {
            e.preventDefault();

            const btn = this;
            const id = $("#saved_headers").val();

            if (id) {
                const html = $(btn).html()
                $.ajax({
                    url: "{{ route('mailer.headers.destroy', ':id') }}".replace(':id', id),
                    type: 'delete',
                    dataType: 'json',
                    beforeSend: () => {
                        $(btn).attr('disabled', true).html(spinner())
                    }
                }).done(function (res) {
                    if (res.status) {
                        swal.fire({
                            title: 'Success',
                            text: 'Header Deleted',
                            icon: 'success'
                        });
                    }
                    const headers = $("#saved_headers");
                    headers.html("");
                    $.each(res.headers, function (i, v) {
                        const option = $("<option></option>", {
                            "value": v.id,
                            "text": v.name
                        });
                        headers.append(option);
                    });
                    headers.selectpicker('refresh');
                }).always(function () {
                    $(btn).attr('disabled', false).html(html)
                })
            }
        });

        $("#option").on('change', function (e) {
            e.preventDefault();
            $("#servers").trigger("change");
        });

        $("#type").on('change', function (e) {
            e.preventDefault();
            $("#servers").trigger("change");
        });

        $("#reloadServers").on("click", function (e) {
            e.preventDefault();

            const btn = this;
            $.ajax({
                url: "{{ route('mailer.ips.servers') }}",
                type: 'get',
                dataType: 'json',
                beforeSend: () => {
                    $(btn).addClass('fa-spin');
                }
            }).done(function (res) {
                const servers = $("#servers");
                servers.html("");
                $.each(res, function (i, v) {
                    const option = $("<option></option>", {
                        "value": v.id,
                        "text": v.name + v.id  + " | " +  v.ip
                    });
                    servers.append(option);
                });
                servers.selectpicker('refresh');
                ips.empty();
                ips.bootstrapDualListbox('refresh', true);
            }).always(function () {
                $(btn).removeClass('fa-spin');
            })
        });

        $("#searchServers").on("click", async function (e) {
            e.preventDefault();
            const {value: text} = await swal.fire({
                input: 'textarea',
                inputLabel: 'Search Servers',
                inputPlaceholder: 'Type your servers here...',
                inputAttributes: {
                    'aria-label': 'Type your servers here'
                },
                showCancelButton: true
            })

            if (text) {
                const servers = [...new Set(text.split("\n").filter((i) => i))];
                const result = [];
                $.each(servers, function (i, v) {
                    $("#servers").find("option").each(function (index) {
                        const data = $(this).text().split("|");
                        const server = data[0].trim().toLowerCase();
                        const ip = data[1].trim();
                        if (server === v.toLowerCase() || ip.includes(v)) {
                            result.push($(this).val());
                        }
                    });
                });
                $("#servers").selectpicker('val', result);
                $("#servers").selectpicker('refresh');
                $("#servers").trigger('change');
            }
        });

       $("#servers").on('change', function (e) {
    e.preventDefault();
    const servers = $(this).val();
    const option = $('#option').val();
    const type = $('#type').val();
    if (servers.length && option && type) {
        $.ajax({
            url: "{{ route('mailer.ips.get') }}",
            type: 'get',
            dataType: "json",
            data: {
                option: option,
                server_ids: servers,
                type: type,
            }
        }).done(function (res) {
            ips.empty();
            $.each(res, (i, v) => {
                const domain_id = (option === 'vmta' || option === 'smtp') ? 0 : v.domain_id;
                const ipId = (option === 'vmta' || option === 'smtp') ? v.ip_id : v.id;
                const username = option === 'smtp' ? `,${v.username}` : (option === 'vmta' ? ',' : '');
                const dns = (option === 'vmta' || option === 'smtp') ? (v.ip_dns || v.dns) : v.dns;
                const vmtaDns = (option === 'vmta' || option === 'smtp') ? `,${v.dns}` : '';
                const val = `${v.ip},${dns},${ipId},${domain_id},${v.server_id},${v.id}${username}${vmtaDns}`;
                const text = `${v.ip},${v.dns}${username}`;
                const listed = v?.history?.ip_listed_spamhaus || v?.history?.ip_listed_spamcop || v?.history?.ip_listed_barracuda || v?.history?.domain_listed_hostkarma || v?.history?.domain_listed_spamhaus_dbl || v?.history?.domain_listed_surbl;

                // Always select the option to move it to the Selected Ips side
                $('<option/>', {
                    style: listed ? 'color:red' : '',
                    value: val,
                    text: text,
                    selected: true  // This ensures the option is moved to Selected Ips
                }).appendTo(ips)
            });
            ips.bootstrapDualListbox('refresh', true);
        });
    } else {
        ips.empty();
        ips.bootstrapDualListbox('refresh', true);
    }
});

        $(".content").on("click", "#searchIps", async function (e) {
            e.preventDefault();
            const {value: text} = await swal.fire({
                input: 'textarea',
                inputLabel: 'Search Ips',
                inputPlaceholder: 'Type your ips here...',
                inputAttributes: {
                    'aria-label': 'Type your ips here'
                },
                showCancelButton: true
            })

            if (text) {
                const ips = [...new Set(text.split("\n").map(i => i.trim()).filter((i) => i))];
                const result = [];
                $.each(ips, function (i, v) {
                    $("#ips").find("option").each(function (index) {
                        const [ip, domain, ,,,, smtp] = $(this).val().toLowerCase().trim().split(',');
                        if(ip === v || domain.includes(v) || smtp?.includes(v)) {
                            result.push($(this).val());
                        }
                    });
                });
                $("#ips").val(result);
                $("#ips").bootstrapDualListbox("refresh");
            }
        });

// Copy IPs from NON-SELECTED (left side) list
$(".content").on("click", "#preCopyIps", async function (e) {
    e.preventDefault();

    // Get the non-selected listbox
    const nonSelectedListbox = $("#bootstrap-duallistbox-nonselected-list_");

    // Check if any items are selected in the non-selected listbox
    const selectedOptions = nonSelectedListbox.find('option:selected');

    let ipsToCopy;

    if (selectedOptions.length > 0) {
        // If items are selected, copy only selected items
        ipsToCopy = selectedOptions.map(function () {
            return this.value.split(',')[0]; // Extract IP from "ip,domain,..." format
        }).get();
    } else {
        // If no items are selected, copy all items
        ipsToCopy = nonSelectedListbox.find('option').map(function () {
            return this.value.split(',')[0]; // Extract IP from "ip,domain,..." format
        }).get();
    }

    if (ipsToCopy.length > 0) {
        copyToClipboard(ipsToCopy.join("\n"));

        // Use toast notification instead of alert
        Toast.fire({
            icon: 'success',
            title: `Copied ${ipsToCopy.length} IPs from available list`
        });
    } else {
        Toast.fire({
            icon: 'warning',
            title: 'No IPs to copy'
        });
    }
});

// Copy domains from NON-SELECTED (left side) list
$(".content").on("click", "#preCopyDomains", async function (e) {
    e.preventDefault();

    // Get the non-selected listbox
    const nonSelectedListbox = $("#bootstrap-duallistbox-nonselected-list_");

    // Check if any items are selected in the non-selected listbox
    const selectedOptions = nonSelectedListbox.find('option:selected');

    let domainsToCopy;

    if (selectedOptions.length > 0) {
        // If items are selected, copy only selected items
        domainsToCopy = selectedOptions.map(function () {
            return this.value.split(',')[1]; // Extract domain from "ip,domain,..." format
        }).get();
    } else {
        // If no items are selected, copy all items
        domainsToCopy = nonSelectedListbox.find('option').map(function () {
            return this.value.split(',')[1]; // Extract domain from "ip,domain,..." format
        }).get();
    }

    if (domainsToCopy.length > 0) {
        copyToClipboard(domainsToCopy.join("\n"));

        Toast.fire({
            icon: 'success',
            title: `Copied ${domainsToCopy.length} domains from available list`
        });
    } else {
        Toast.fire({
            icon: 'warning',
            title: 'No domains to copy'
        });
    }
});

// Copy IPs from SELECTED (right side) list
$(".content").on("click", "#copyIps", async function (e) {
    e.preventDefault();

    // Get the selected listbox
    const selectedListbox = $("#bootstrap-duallistbox-selected-list_");

    // Check if any items are selected in the selected listbox
    const selectedOptions = selectedListbox.find('option:selected');

    let ipsToCopy;

    if (selectedOptions.length > 0) {
        // If items are selected, copy only selected items
        ipsToCopy = selectedOptions.map(function () {
            return this.value.split(',')[0]; // Extract IP from "ip,domain,..." format
        }).get();
    } else {
        // If no items are selected, copy all items
        ipsToCopy = selectedListbox.find('option').map(function () {
            return this.value.split(',')[0]; // Extract IP from "ip,domain,..." format
        }).get();
    }

    if (ipsToCopy.length > 0) {
        copyToClipboard(ipsToCopy.join("\n"));

        Toast.fire({
            icon: 'success',
            title: `Copied ${ipsToCopy.length} selected IPs`
        });
    } else {
        Toast.fire({
            icon: 'warning',
            title: 'No IPs to copy'
        });
    }
});

// Copy domains from SELECTED (right side) list
$(".content").on("click", "#copyDomains", async function (e) {
    e.preventDefault();

    // Get the selected listbox
    const selectedListbox = $("#bootstrap-duallistbox-selected-list_");

    // Check if any items are selected in the selected listbox
    const selectedOptions = selectedListbox.find('option:selected');

    let domainsToCopy;

    if (selectedOptions.length > 0) {
        // If items are selected, copy only selected items
        domainsToCopy = selectedOptions.map(function () {
            return this.value.split(',')[1]; // Extract domain from "ip,domain,..." format
        }).get();
    } else {
        // If no items are selected, copy all items
        domainsToCopy = selectedListbox.find('option').map(function () {
            return this.value.split(',')[1]; // Extract domain from "ip,domain,..." format
        }).get();
    }

    if (domainsToCopy.length > 0) {
        copyToClipboard(domainsToCopy.join("\n"));

        Toast.fire({
            icon: 'success',
            title: `Copied ${domainsToCopy.length} selected domains`
        });
    } else {
        Toast.fire({
            icon: 'warning',
            title: 'No domains to copy'
        });
    }
});

        $("#total_send").on("keyup input", function (e) {
            updateFractionIpEdit();
        });

        function updateFractionIpEdit() {
            const val = parseInt($("#total_send").val());
            const ips = $("#ips").val();
            const totalIps = ips.length > 0 ? ips.length : 1;
            if (!isNaN(val) && val > 0) {
                $("#fraction_ip").val(window.fractionIp(val, totalIps));
            }
        }

        $("#change").on('click', function (e) {
            e.preventDefault();
            const btn = this;
            const html = $(btn).html();
            const send_progress = $("#start_from").val();
            if (send_progress > {{ $campaign->send_count }}) {
                swal.fire({
                    title: 'Error',
                    text: 'You can not change send progress to greater than send count {{ $campaign->send_count }}',
                    icon: 'error'
                });
                return false;
            }
            $.ajax({
                url: '{{ route('mailer.campaigns.progress', $campaign) }}',
                type: 'put',
                dataType: "json",
                data: {
                    send_progress,
                },
                beforeSend: () => {
                    $(btn).attr("disabled", true).html(spinner())
                }
            }).done(function (res) {
                swal.fire({
                    icon: 'success',
                    title: res.data
                });
            }).always(function (res) {
                $(btn).attr("disabled", false).html(html);
            });
        });

        $("#generate_links").on('click', function (e) {
            e.preventDefault();
            const btn = this;
            const html = $(btn).html();
            const body = editorBody.session.getValue();
            if (!body) {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Body is empty'
                });
                return;
            }

            $.ajax({
                url: '{{ route('mailer.campaigns.generate_body', $campaign) }}',
                type: 'post',
                dataType: "json",
                data: {
                    body
                },
                beforeSend: () => {
                    $(btn).attr("disabled", true).html(html + " " + spinner())
                }
            }).done(function (res) {
                editorBody.session.setValue(res.data);
            }).always(function (res) {
                $(btn).attr("disabled", false).html(html);
            });
        });

        $("#sleep_email").on("keyup input", function (e) {
            const val = $(this).val();
            const ips = $("#ips").val();
            const totalIps = ips.length > 0 ? ips.length : 1;
            if (val > 0) {
                const speed = 60000 / $(this).val();
                $("#estimate_speed").html(`&sime; ${speed.toFixed()}/min <br>&sime; ${(speed / totalIps).toFixed()}/min/ip`)
            } else {
                $("#estimate_speed").html('')
            }
        });

        //Toast
        const Toast = swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 5000
        });

        const test_campaign = function (mode) {
            mode = mode || 'batch';
            const return_path = $("#return_path").val();
            const email_test = $("#email_test").val().split("\n").map(i => i.trim()).filter((i) => i);
            const from = $("#from").val();
            const subjects = $("#subject").val();
            const headerPayload = campaignHeaderTabs.collectPayload();
            const domain = $("#domain").val();
            const negative = $("#negative").val();
            const creative = $("#creative").val();
            const body = editorBody.session.getValue();
            const option = $("#option").val();
            const type = $("#type").val();
            const servers = $("#servers").val();
            let ips = $("#ips").val();
            if (mode === 'single' && Array.isArray(ips) && ips.length > 1) {
                ips = [ips[Math.floor(Math.random() * ips.length)]];
            }

            const content_type = $("#content_type").val();
            const charset = $("#charset").val();
            const transfer = $("#transfer").val();

            const placeholder_combination = $("#placeholder_combination").is(':checked') ? 1 : 0;
            const placeholder_all_combination = $("#placeholder_all_combination").is(':checked') ? 1 : 0;
            editorPlaceHolders.removeEmptyRows();
            const placeholders = editorPlaceHolders.getAllValue().map(p => {
                return {
                    data: p.data.split("\n").map(i => i.trim()).filter((i) => i),
                    rotate_every: p.rotate_every
                }
            }).filter((p) => p.data.length > 0 && p.rotate_every > 0);

            const send_type = $("#send_type").val().toLowerCase();
            const boxes_selected = $("#boxes_selected").val();
            const boxes_rotate_every = $("#boxes_rotate_every").val();

            $.ajax({
                url: '{{ route('mailer.campaigns.test_manuel') }}',
                type: 'POST',
                dataType: "json",
                global: false,
                data: {
                    campaign_id: {{ $campaign->id }},
                    return_path,
                    email_test,
                    from,
                    subjects,
                    header_2: headerPayload.header_2,
                    header_rotate_every: headerPayload.header_rotate_every,
                    domain,
                    negative,
                    creative,
                    content_type,
                    charset,
                    transfer,
                    body: b64encode(body),
                    option,
                    type,
                    servers,
                    ips,
                    placeholder_combination,
                    placeholder_all_combination,
                    placeholders,
                    send_type,
                    boxes_selected,
                    boxes_rotate_every
                }
            }).done(function (res) {
                Toast.fire({
                    icon: 'success',
                    title: res.data
                });
            });
        }

        const edit_campaign = function () {
            const from = $("#from").val();
            const subjects = $("#subject").val();
            const creative = $("#creative").val();
            const content_type = $("#content_type").val();
            const charset = $("#charset").val();
            const transfer = $("#transfer").val();
            const return_path = $("#return_path").val();
            const email_test = $("#email_test").val().split("\n").map(i => i.trim()).filter((i) => i);
            const headerPayload = campaignHeaderTabs.collectPayload();
            const domain = $("#domain").val();
            const negative = $("#negative").val();
            const body = editorBody.session.getValue();
            const option = $("#option").val();
            const type = $("#type").val();
            const servers = $("#servers").val();
            const ips = $("#ips").val();

            const placeholder_combination = $("#placeholder_combination").is(':checked') ? 1 : 0;
            const placeholder_all_combination = $("#placeholder_all_combination").is(':checked') ? 1 : 0;
             editorPlaceHolders.removeEmptyRows();
            const placeholders = editorPlaceHolders.getAllValue().map(p => {
                return {
                    data: p.data.split("\n").map(i => i.trim()).filter((i) => i),
                    rotate_every: p.rotate_every
                }
            }).filter((p) => p.data.length > 0 && p.rotate_every > 0);

            const total_send = $("#total_send").val();
            const fraction_ip = $("#fraction_ip").val();
            const sleep_email = $("#sleep_email").val();
            const sleep_ip = $("#sleep_ip").val();
            const seed_every = $("#seed_every").val();
            const bounce_limit = $("#bounce_limit").val();
            const send_type = $("#send_type").val();
            const duplicate_data = $("#duplicate_data").val();

            const boxes_selected = $("#boxes_selected").val();
            const boxes_rotate_every = $("#boxes_rotate_every").val();

            $.ajax({
                url: '{{ route('mailer.campaigns.update', $campaign) }}',
                type: 'PUT',
                dataType: "json",
                global: false,
                data: {
                    from,
                    subjects,
                    creative,
                    content_type,
                    charset,
                    transfer,
                    return_path,
                    email_test,
                    header_2: headerPayload.header_2,
                    header_rotate_every: headerPayload.header_rotate_every,
                    domain,
                    negative,
                    body: b64encode(body),
                    option,
                    type,
                    servers,
                    ips,

                    placeholder_combination,
                    placeholder_all_combination,
                    placeholders,

                    total_send,
                    fraction_ip,
                    sleep_email,
                    sleep_ip,
                    seed_every,
                    bounce_limit,
                    send_type,
                    duplicate_data,

                    boxes_selected,
                    boxes_rotate_every
                }
            }).done(function (res) {
                swal.fire({
                    icon: 'success',
                    title: res.data
                }).then(function () {
                    window.location.href = '{{ route('mailer.campaigns.index') }}';
                });
            });
        };

        const pmta_admin = () => {
            const servers = $("#servers").val();
            if (servers.length) {
                window.open('{{ route('mailer.pmta.index') }}' + '?' + $.param({'ids': servers}), '_blank');
            }
        };

			const isp_config = function () {
			window.open('{{ route('mailer.pmta.isp_config') }}', '_blank');
			};

			const vmta = function () {
				$('#vmtaQuickModal').modal('show');
			};

        const pmta_errors = function () {
            const type = $("#option").val();
            const ips = $("#ips").val();
            if (!ips.length || !type) {
                swal.fire({
                    icon: 'error',
                    title: 'Please select option and ips'
                });
                return;
            }

            $.ajax({
                url: "{{ route('mailer.pmta.extract_errors') }}",
                type: 'post',
                dataType: "json",
                data: {
                    'ips': ips,
                    'type': type
                }
            }).done((res) => {

                const link = new URL("{{ route('mailer.pmta.extract_errors') }}") + '?' + $.param({'id': res.data});
                const iframe = `<iframe src="${link.toString()}" width="100%" scrolling="no" frameborder="0"></iframe>`
                bootbox.dialog({
                    scrollable: true,
                    closeButton: true,
                    size: "xl",
                    message: iframe,
                    onEscape: true,
                    centerVertical: true
                }).on('shown.bs.modal', function () {
                    iFrameResize({}, 'iframe');
                }).on('hidden.bs.modal', function () {
                    $("iframe").remove();
                })
            });
        }

        $("#edit_campaign").on('click', function (e) {
            e.preventDefault();
            edit_campaign();
        });

        $("#test_campaign").on('click', function (e) {
            e.preventDefault();
            test_campaign();
        });

        $("#pmta_admin").on('click', function (e) {
            e.preventDefault();
            pmta_admin();
        });

            $("#vmta").on('click', function (e) {
                e.preventDefault();
                vmta();
            });

            $("#isp_config").on('click', function (e) {
                e.preventDefault();
                isp_config();
            });

        $("#pmta_errors").on('click', function (e) {
            e.preventDefault();
            pmta_errors();
        });

        $('#fetchBoxes').click(function(e) {
            e.preventDefault();
            var $btn = $(this);
            var originalHtml = $btn.html();

            $.ajax({
                url: '{{ route("mailer.warmups.boxes.fetch_all") }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'load': 1
                },
                beforeSend: function() {
                    $btn.attr('disabled', true).html(spinner());
                }
            })
                .done(function(response) {
                    swal.fire({
                        icon: 'success',
                        title: response.data
                    }).then(function() {
                        //replace the boxes list with the new data
                        if(response.sources) {
                            const boxesSelect = $('#boxes_selected');
                            boxesSelect.empty();
                            $.each(response.sources, function(i, v) {
                                const option = $("<option></option>", {
                                    "value": v.id,
                                    "text": v.subject
                                });
                                boxesSelect.append(option);
                            });
                            boxesSelect.selectpicker('refresh');
                        }
                    });
                })
                .always(function() {
                    $btn.attr('disabled', false).html(originalHtml);
                });
        });

// Single, clean context menu
$.contextMenu({
    selector: '#data',
    zIndex: 3,
    items: {
        edit: {
            name: 'SAVE/EDIT',
            icon: "fas fa-save",
            callback: function () {
                edit_campaign();
            }
        },
        normal_test: {
            name: "NORMAL TEST",
            icon: "fas fa-paper-plane",
            callback: function() {
                test_campaign('single');
            }
        },
        batch_test: {
            name: "BATCH TEST",
            icon: "fas fa-layer-group",
            callback: function() {
                test_campaign('batch');
            }
        },
        @if(config('settings.vade_enabled', false))
        vade_test: {
            name: "VADE TEST",
            icon: "fas fa-shield-alt",
            callback: function() {
            if (typeof window.vadeTest === 'function') {
            window.vadeTest();
            } else {
            console.error('VADE TEST is not available on this page.');
            }
}
        },
        @endif
        pmta_admin: {
            name: "PMTA ADMIN",
            icon: "fas fa-desktop",
            callback() {
                pmta_admin();
            }
        },
        vmta: {
            name: "VMTA",
            icon: "fas fa-cog",
            callback() {
                vmta();
            }
        },
        pmta_errors: {
            name: "PMTA ERRORS",
            icon: "fas fa-exclamation-triangle",
            callback() {
                pmta_errors();
            }
        },
        isp_config: {
            name: "ISP CONFIG",
            icon: "fas fa-file-code",
            callback() {
                isp_config();
            }
        },
    }
});

		$('#create_campaign_btn').on('click', function(e) {
            e.preventDefault();
            edit_campaign();
		});

// Regular Test Function — defined unconditionally so the NORMAL TEST
// button onclick="regularTest()" works even when Vade module is disabled.
window.regularTest = function() {
    var ips = $("#ips").val() || [];
    if (ips.length <= 1) {
        test_campaign('single');
        return;
    }
    swal.fire({
        title: 'Multiple IPs/VMTAs selected',
        text: ips.length + ' selected. How do you want to test?',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane mr-1"></i> Normal Test (1)',
        denyButtonText: '<i class="fas fa-layer-group mr-1"></i> Batch Test (all)',
        cancelButtonText: 'Cancel',
        reverseButtons: false,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary btn-lg m-1',
            denyButton:    'btn btn-info btn-lg m-1',
            cancelButton:  'btn btn-secondary btn-lg m-1'
        }
    }).then(function (result) {
        if (result.isConfirmed)      test_campaign('single');
        else if (result.isDenied)    test_campaign('batch');
    });
};

@if(config('settings.vade_enabled', false))
// ===== FIXED VADE TEST WITH PROPER INTERVAL MANAGEMENT AND QUEUE RESUME =====

// Store the last result for navigation and active interval
let lastVadeResult = null;
let lastTotalFound = 1;
let activeCheckInterval = null; // Track the active interval

// Vade Test Function - properly exposed to window
window.vadeTest = function () {
    console.log('vadeTest function called'); // Debug log

    const from = $("#from").val();
    const subjects = $("#subject").val();
    const return_path = $("#return_path").val();
    const headerPayload = campaignHeaderTabs.collectPayload(true);
    const negative = $("#negative").val();
    const body = editorBody.session.getValue();
    const option = $("#option").val();
    const type = $("#type").val();
    const servers = $("#servers").val();
    const ips = $("#ips").val();
    const content_type = $("#content_type").val();
    const charset = $("#charset").val();
    const transfer = $("#transfer").val();

    const placeholder_combination = $("#placeholder_combination").is(':checked') ? 1 : 0;
    const placeholder_all_combination = $("#placeholder_all_combination").is(':checked') ? 1 : 0;
    editorPlaceHolders.removeEmptyRows();
    const placeholders = editorPlaceHolders.getAllValue().map(p => {
        return {
            data: p.data.split("\n").map(i => i.trim()).filter((i) => i),
            rotate_every: p.rotate_every
        }
    }).filter((p) => p.data.length > 0 && p.rotate_every > 0);
    const send_type = $("#send_type").val();

    const boxes_selected = $("#boxes_selected").val();
    const boxes_rotate_every = $("#boxes_rotate_every").val();

    // Validation checks
    if (!ips || ips.length === 0) {
        swal.fire({
            icon: 'error',
            title: 'Missing IPs',
            text: 'Please select at least one IP for Vade testing'
        });
        return;
    }

    campaignHeaderTabs.flushActive();
    const vadeHeaderText = campaignHeaderTabs.headers[campaignHeaderTabs.activeIndex] || '';
    if (!vadeHeaderText.trim()) {
        swal.fire({
            icon: 'error',
            title: 'Missing Header',
            text: 'Please enter email header for Vade testing'
        });
        return;
    }

    // Send helper — runs the actual AJAX with whatever IP set the user picked.
    // Single source of truth for the request body so Normal Test and Batch Test
    // can both call into it without duplicating the payload.
    const sendVadeTest = (ipsToSend) => {
        if (activeCheckInterval) {
            clearInterval(activeCheckInterval);
            activeCheckInterval = null;
            console.log('Cleared previous interval');
        }

        swal.fire({
            title: 'Sending Vade Test...',
            text: 'Preparing test email for Vade analysis',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => { swal.showLoading(); }
        });

        $.ajax({
            url: '{{ route('mailer.campaigns.vade_test') }}',
            type: 'POST',
            dataType: "json",
            data: {
                from,
                subjects,
                return_path,
                header_2: headerPayload.header_2,
                header_rotate_every: headerPayload.header_rotate_every,
                negative,
                content_type,
                charset,
                transfer,
                body: b64encode(body),
                servers,
                option,
                type,
                ips: ipsToSend,
                placeholder_combination,
                placeholder_all_combination,
                placeholders,
                send_type,
                boxes_selected,
                boxes_rotate_every
            }
        }).done(function (res) {
            if (res.success) {
                resumeVadeQueue(servers, function() {
                    const firstIp = ipsToSend[0].split(',')[0]; // "ip,domain,..." → ip
                    checkVadeResults(firstIp, 'IP');
                });
            } else {
                swal.fire({ icon: 'error', title: 'Test Failed', text: res.error || 'Unknown error occurred' });
            }
        }).fail(function(xhr) {
            swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.error || 'Failed to send test email' });
        });
    };

    // One IP — just send. Multiple — same 3-button picker that Normal Test uses.
    if (ips.length === 1) {
        sendVadeTest(ips);
        return;
    }

    swal.fire({
        title: 'Multiple IPs/VMTAs selected',
        text: ips.length + ' selected. How do you want to test?',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane mr-1"></i> Normal Test (1)',
        denyButtonText:    '<i class="fas fa-layer-group mr-1"></i> Batch Test (all)',
        cancelButtonText:  'Cancel',
        reverseButtons: false,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary btn-lg m-1',
            denyButton:    'btn btn-info btn-lg m-1',
            cancelButton:  'btn btn-secondary btn-lg m-1'
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            // Normal Test: pick one IP at random so the test isn't biased to the
            // first selection on every click.
            const randomIp = ips[Math.floor(Math.random() * ips.length)];
            sendVadeTest([randomIp]);
        } else if (result.isDenied) {
            sendVadeTest(ips);
        }
        // Cancel: do nothing.
    });
};

// Function to resume PMTA queue forvade.email
function resumeVadeQueue(servers, callback) {
    console.log('Resuming vade.email queue for servers:', servers);

    swal.update({
        title: 'Resuming Queue...',
        text: 'Preparing vade.email queue for delivery'
    });

    $.ajax({
        url: '/mailer/campaigns/resume-vade-queue',
        type: 'POST',
        dataType: "json",
        data: {
            servers: servers,
            _token: $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(res) {
        console.log('Queue resume response:', res);
        // Continue to callback regardless of response
        // Some servers might not need queue resume
        if (callback) callback();
    }).fail(function(xhr) {
        console.warn('Queue resume failed, continuing anyway:', xhr);
        // Continue even if queue resume fails
        if (callback) callback();
    });
}

// Function to stop any active checking
function stopVadeChecking() {
    if (activeCheckInterval) {
        clearInterval(activeCheckInterval);
        activeCheckInterval = null;
        console.log('Stopped Vade checking interval');
    }
}

// Function to check Vade results with proper cleanup
@include('mailer._partials.vade_check_js')

const checkVadeResults = function(searchQuery, searchType = 'IP', isRecheck = false) {
    let attempts = 0;
    VadeCheck.begin();
    const maxAttempts = 12; // asked every 5 seconds, for a minute: a test is looked for among what arrived since it was sent

    // Show loading immediately
    swal.fire({
        title: isRecheck ? 'Rechecking Results...' : 'Test Sent! Checking Results...',
        text: `Searching by ${searchType}: ${searchQuery}`,
        width: '750px',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            swal.showLoading();
        }
    });

    // Store the interval so we can clear it later
    activeCheckInterval = setInterval(() => {
        attempts++;

        $.ajax({
            url: '{{ route('mailer.campaigns.check_vade_results') }}',
            type: 'POST',
            dataType: "json",
            data: {
                search_query: searchQuery,
                search_type: searchType,
                email_count: '1' // Check last 1 emails for better chance of finding it
            }
        }).done(function(res) {
            // what PowerMTA did with the test comes first: refused by the mail server (that is
            // the result), or not out of PowerMTA yet (this look does not count)
            const pmta = VadeCheck.handle(res, {searchQuery, stop: stopVadeChecking, recheck: () => checkVadeResults(searchQuery, searchType, true)});
            if (pmta === 'stop') return;
            if (pmta === 'wait') { attempts--; return; }
            if (res.success && res.result) {
                // IMPORTANT: Stop checking immediately when results are found
                stopVadeChecking();

                // Store results for navigation
                lastVadeResult = res.result;
                lastTotalFound = res.total_found || 1;
                showVadeResults(res.result, res.total_found);
            } else if (attempts >= maxAttempts) {
                // Stop checking when max attempts reached
                stopVadeChecking();

                const diag = res && res.ip_diagnostics ? res.ip_diagnostics : null;
                const scanned = res && res.scanned ? res.scanned : 0;
                const diagHtml = diag && diag.ips_seen && diag.ips_seen.length ? `
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                    <p style="margin-bottom: 10px;"><strong>Diagnostics:</strong></p>
                    <div style="background: #fff3cd; padding: 12px; border-radius: 5px; font-size: 13px; border-left: 3px solid #ffc107;">
                        <div style="margin-bottom: 8px;">Scanned <strong>${scanned}</strong> recent email${scanned === 1 ? '' : 's'} in: <code>${diag.folders.join(', ')}</code></div>
                        <div style="margin-bottom: 6px;">The IP <code>${searchQuery}</code> was not present in any of their headers.</div>
                        <div>IPs we did see: <code style="word-break: break-all;">${diag.ips_seen.join(', ')}</code></div>
                    </div>
                ` : (scanned ? `
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                    <div style="background: #fff3cd; padding: 12px; border-radius: 5px; font-size: 13px; border-left: 3px solid #ffc107;">
                        Scanned <strong>${scanned}</strong> recent email${scanned === 1 ? '' : 's'} — no IPs were extractable from their headers.
                    </div>
                ` : '');

                swal.fire({
                    icon: 'warning',
                    title: 'Vade Results Not Found',
                    width: '750px',
                    html: `
                        <div style="text-align: left; padding: 20px;">
                            <p style="margin-bottom: 15px;">Vade results not found after checking.</p>
                            <p style="margin-bottom: 15px;">The email may still be processing or there might be a delivery issue.</p>
                            <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                            <p style="margin-bottom: 10px;"><strong>Search Details:</strong></p>
                            <div style="background: #f8f9fa; padding: 12px; border-radius: 5px; font-size: 14px;">
                                <div style="margin-bottom: 8px;">
                                    <strong>Type:</strong> <code style="background: #e9ecef; padding: 2px 6px; border-radius: 3px;">${searchType}</code>
                                </div>
                                <div>
                                    <strong>Query:</strong> <code style="background: #e9ecef; padding: 2px 6px; border-radius: 3px;">${searchQuery}</code>
                                </div>
                            </div>
                            ${diagHtml}
                        </div>
                    `,
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fas fa-redo"></i> Recheck Now',
                    confirmButtonColor: '#007bff',
                    cancelButtonText: 'Close',
                    footer: '<a href="{{ route('mailer.tools.vade.index') }}" target="_blank" style="color: #007bff;"><i class="fas fa-external-link-alt"></i> Check Vade Tool manually</a>',
                    width: '750px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User clicked recheck button - immediately show loading
                        checkVadeResults(searchQuery, searchType, true);
                    }
                });
            } else {
                // Update progress
                swal.update({
                    text: `Searching by ${searchType}: ${searchQuery} (Attempt ${attempts})`
                });
            }
        }).fail(function(xhr) {
            console.error('Vade check failed:', xhr);
            if (attempts >= maxAttempts) {
                stopVadeChecking();
                swal.fire({
                    icon: 'error',
                    title: 'Error Checking Results',
                    text: 'Failed to check Vade results due to connection error',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fas fa-redo"></i> Retry',
                    confirmButtonColor: '#007bff',
                    cancelButtonText: 'Close',
                    footer: '<a href="{{ route('mailer.tools.vade.index') }}" target="_blank" style="color: #007bff;"><i class="fas fa-external-link-alt"></i> Check Vade Tool manually</a>'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User clicked retry button - immediately show loading
                        checkVadeResults(searchQuery, searchType, true);
                    }
                });
            }
        });
    }, 5000); // every 5 seconds (the answer takes about one)

    console.log('Started Vade checking interval:', activeCheckInterval);
};

// (Old formatAuthStatus removed — see VadeModal.formatAuthStatus in _partials/vade_modal.)

// Enhanced Vade Results Display Function — now a thin wrapper around the
// shared VadeModal API in _partials/vade_modal.
const showVadeResults = function(result, totalFound = 1) {
    return VadeModal.show(result, {
        totalFound: totalFound,
        onClose: () => { stopVadeChecking(); },
    });
};

$(document).on('click', '#vade_test_btn', function (e) {
    e.preventDefault();

    if (typeof window.vadeTest !== 'function') {
        console.error('VADE TEST is not available on this page.');
        return;
    }

    window.vadeTest();
});
// Enhanced CSS for Vade results
$('<style>')
    .prop('type', 'text/css')
    .html(`
        .vade-results-popup {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .vade-html-container {
            text-align: left !important;
            padding: 0 !important;
        }
        .vade-results pre {
            background: white;
            color: #212529;
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', Consolas, monospace;
            font-size: 12px;
            line-height: 1.5;
        }
        .vade-results .btn {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .vade-results .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .vade-results code {
            font-family: 'Courier New', Consolas, monospace;
        }
        .swal2-popup.vade-results-popup {
            padding: 0 !important;
        }
        .swal2-title {
            padding: 20px 20px 10px 20px;
            font-size: 24px;
        }
        .swal2-html-container.vade-html-container {
            margin: 0 !important;
            padding: 0 20px 20px 20px !important;
        }
    `)
    .appendTo('head');

// Debug: Test if functions are properly exposed
console.log('Vade functions loaded:', {
    vadeTest: typeof window.vadeTest,
    regularTest: typeof window.regularTest,
    resumeVadeQueue: typeof resumeVadeQueue,
    stopVadeChecking: typeof stopVadeChecking
});

// ===== END OF FIXED VADE TEST IMPLEMENTATION =====
@endif
    </script>

    {{-- Shared Vade result modal (source viewer + body viewer + window.VadeModal API) --}}
    @if(config('settings.vade_enabled', false))
        @include('mailer._partials.vade_modal')
    @endif
@endsection
