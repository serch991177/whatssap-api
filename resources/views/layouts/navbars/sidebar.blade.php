<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ _('WD') }}</a>
            <a href="#" class="simple-text logo-normal">{{ _('White Dashboard') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ _('Dashboard') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Laravel Examples') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ _('User Profile') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#estados-whatssap" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Estados Whatsapp') }}</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse show" id="estados-whatssap">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'Whatsapp State Messages') class="active " @endif>
                            <a href="{{ route('pages.whatsapp.estados') }}">
                                <i class="tim-icons icon-atom"></i>
                                <p>{{ _('Mensajes Whatsapp') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'Estados Whatsapp Reporte') class="active " @endif>
                            <a href="{{ route('pages.estadoswhatsappreporte') }}">
                                <i class="tim-icons icon-atom"></i>
                                <p>{{ _('Reporte Global') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#mensajes-whatssap" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Mensajes Enviados Whatsapp') }}</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse show" id="mensajes-whatssap">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'Whatsapp Messages') class="active " @endif>
                            <a href="{{ route('pages.whatsapp.messages') }}">
                                <i class="tim-icons icon-atom"></i>
                                <p>{{ _('Mensajes Enviados') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'Estados Whatsapp Reporte') class="active " @endif>
                            <a href="{{ route('pages.estadoswhatsappreporte') }}">
                                <i class="tim-icons icon-atom"></i>
                                <p>{{ _('Reporte Global') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#mensajes-respondidos" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Mensajes Respondidos Whatsapp') }}</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse show" id="mensajes-respondidos">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'Whatsapp Incoming Messages') class="active " @endif>
                            <a href="{{ route('pages.mensajesrespondidos') }}">
                                <i class="tim-icons icon-atom"></i>
                                <p>{{ _('Mensajes Respondidos') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'Estados Whatsapp Reporte') class="active " @endif>
                            <a href="{{ route('pages.estadoswhatsappreporte') }}">
                                <i class="tim-icons icon-atom"></i>
                                <p>{{ _('Reporte Global') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
           
            
            
        </ul>
    </div>
</div>
