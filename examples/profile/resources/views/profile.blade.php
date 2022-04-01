<!DOCTYPE html>
<html class="yoti-html">

<head>
    <meta charset="utf-8">
    <title>Yoti client example</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/profile.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" />
</head>

<body class="yoti-body">
    <main class="yoti-profile-layout">
        <section class="yoti-profile-user-section">

            <div class="yoti-profile-picture-powered-section">
                <span class="yoti-profile-picture-powered">Powered by</span>
                <a href="https://www.yoti.com" target="_blank">
                    <img class="yoti-logo-image" src="/assets/images/logo.png" srcset="/assets/images/logo@2x.png 2x" alt="Yoti" />
                </a>
            </div>

            <div class="yoti-profile-picture-section">
                @if ($selfie)
                <div class="yoti-profile-picture-area">
                    <img src="{{ $selfie->getValue()->getBase64Content() }}" class="yoti-profile-picture-image" alt="Yoti" />
                    <i class="yoti-profile-picture-verified-icon"></i>
                </div>
                @endif

                @if ($fullName)
                <div class="yoti-profile-name">
                    {{ $fullName->getValue() }}
                </div>
                @endif
            </div>
        </section>

        <section class="yoti-attributes-section">
            <a href="/">
                <img class="yoti-company-logo" src="/assets/images/company-logo.jpg" alt="company logo">
            </a>

            <div class="yoti-attribute-list-header">
                <div class="yoti-attribute-list-header-attribute">Attribute</div>
                <div class="yoti-attribute-list-header-value">Value</div>
                <div>Anchors</div>
            </div>

            <div class="yoti-attribute-list-subheader">
                <div class="yoti-attribute-list-subhead-layout">
                    <div>S / V</div>
                    <div>Value</div>
                    <div>Sub type</div>
                </div>
            </div>

            <div class="yoti-attribute-list">
                @foreach($profileAttributes as $item)
                    @if ($item['obj'])
                    <div class="yoti-attribute-list-item">
                        <div class="yoti-attribute-name">
                            <div class="yoti-attribute-name-cell">
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="yoti-attribute-name-cell-text">{{ $item['name'] }}</span>
                            </div>
                        </div>
                        <div class="yoti-attribute-value">
                            <div class="yoti-attribute-value-text">
                            @switch ($item['name'])
                                @case ('Age Verification')
                                    @include('partial/ageverification', ['ageVerification' => $item['age_verification']])
                                    @break
                                @case ('Structured Postal Address')
                                    @include('partial/address', ['address' => $item['obj']->getValue()])
                                    @break
                                @default
                                    @include('partial/attribute', ['value' => $item['obj']->getValue()])
                            @endswitch
                            </div>
                        </div>
                        <div class="yoti-attribute-anchors-layout">
                            <div class="yoti-attribute-anchors-head -s-v">S / V</div>
                            <div class="yoti-attribute-anchors-head -value">Value</div>
                            <div class="yoti-attribute-anchors-head -subtype">Sub type</div>

                            @foreach($item['obj']->getAnchors() as $anchor)
                                <div class="yoti-attribute-anchors -s-v">{{ $anchor->getType() }}</div>
                                <div class="yoti-attribute-anchors -value">{{ $anchor->getValue() }}</div>
                                <div class="yoti-attribute-anchors -subtype">{{ $anchor->getSubType() }}</div>
                            @endforeach

                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
    </main>
</body>

</html>