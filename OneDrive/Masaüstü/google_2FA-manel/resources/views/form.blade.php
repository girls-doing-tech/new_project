<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <!-- Link your stylesheet here -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  </head>
  <body>
    <div class="tab-container">
      <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'recipientTab')">
          منصة سوا
        </button>
        <button class="tablinks" onclick="openTab(event, 'amountTab')">
          سند صرف
        </button>
        <button class="tablinks" onclick="openTab(event, 'confirmTab')">
          اعتماد جديد
        </button>
      </div>

      <div id="recipientTab" class="tabcontent">
        <form action="process_payment.php" method="post">
          <div class="input-container">
            <label for="recipient">اسم المستفيد:</label>
            <input type="text" placeholder="Input 1">
            <label for="recipient">رقم المستفيد:</label>
            <input type="text" placeholder="Input 2">
          </div>
          <div class="input-container">
          <label for="recipient">الجهة:</label>
          <select class="select2 form-control" id="type" required>

            <?php
            $curl_handle = curl_init();

            $url = "https://secure.disoft-tech.com/Disoft_RestFul_Api/resources/Sawa_Api/S1_Branches?pUName=10921372&pUToken=fvoxk8djufcpez8dus91vacy5dp19jza&pB_Userid=34&pCompanyid=2";

            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);

            $curl_data = curl_exec($curl_handle);
            curl_close($curl_handle);

            $response_data = json_decode($curl_data);
            $user_data = $response_data->S_BRANCHESLIST;

            foreach ($user_data as $user) {
                echo '<option value="' . $user->DB_BRANCH_NAME_AR . '">' . $user->DB_BRANCH_NAME_AR . '</option>';
            }
            ?>

                </select>
        </div>
        <div class="input-container">
          <label class="col-sm-2 col-form-label">الوجهة (المدينة)</label>

            <input id="City" type="text" onchange="" class="form-control" disabled="">

        </div>
        <div class="input-container">
          <label class="col-sm-2 col-form-label"">العملة المرسلة</label>
          <div class="col-sm-8">
            <select id="SCur" class="form-control" onchange="FeesRateCalc('SCur');" style="color: rgb(4, 129, 96);">
                <option style="color:#354168;" color="#354168" value="0">إختر العملة المرسلة</option>
                                              <option style="color:#8713a4;" color="#8713a4" value="2">ليرة&nbsp;تركية</option>
                                              <option style="color:#048160;" color="#048160" value="3">دولار</option>
                                              <option style="color:#8b4d00;" color="#8b4d00" value="4">يورو</option>
                                          </select>
          </div>

            <div class="input-container"">
              <label class="col-sm-2 col-form-label">المبلغ المرسل</label>
              <div class="col-sm-2">
                <input id="SAM" type="text" onchange="FeesRateCalc('SAM');" class="form-control NumberOnly" disabled="">
              </div>

          </div>
        </div>

        <!--------------------------------copy-->
        <div class="input-container">

            <label class="col-sm-2 col-form-label">المبلغ بالسوري</label>
            <div class="col-sm-3">
              <input id="SYDAM" type="text" onfocus="this.select()" onblur="SYDAMCalc();" class="form-control NumberOnly">

          </div>
        </div>

        <div class="input-container">

            <label class="col-sm-4 col-form-label">العملة المسلمة</label>
            <div class="col-sm-4">
              <select id="RCur" class="form-control" onchange="FeesRateCalc('RCur');" style="color: rgb(58, 173, 255);">





                                            <option value="1" color="#3aadff" style="color:#3aadff;">ليرة سورية</option></select>

          </div>


            <div class="input-container">
            <label class="col-sm-4 col-form-label">المبلغ المسلم</label>
            <div class="col-sm-8">
              <input id="RAM" type="text" disabled="" class="form-control NumberOnly">
            </div>
          </div>
        </div>
        <div class="input-container">

            <label class="col-sm-4 col-form-label">الأجور</label>
            <div class="col-sm-8">
              <input id="FEES" value="0" type="text" disabled="" class="form-control feescolor NumberOnly">
            </div>


          <div class="form-group row">
            <label class="col-sm-4 col-form-label">الصرف</label>
            <div class="col-sm-8">
              <input id="TEXRATE" value="0" type="text" disabled="" class="form-control feescolor NumberOnly">
            </div>
          </div>
        </div>
        <div class="input-container">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">ملاحظة</label>
            <div class="col-sm-10">
              <input id="Note" type="text" class="form-control">
            </div>
          </div>
        </div>
        <div class="input-container">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">عنوان الفرع</label>
            <div class="col-sm-8">
              <div id="BHADD" class="form-control">يمكنك الإستلام من أي فرع من فرع شركة سوا - الهرم - المتحدة - الفؤاد</div>
            </div>
            <div class="col-sm-2">
              <span style="margin-top:8px" class="s7-copy-file btn btn-light" data-clipboard-action="copy" data-clipboard-target="#BHADD">نسخ</span>
            </div>
          </div>
        </div>


          <br />

          <input type="submit" value="ارسال" />
        </form>
      </div>

      <div id="amountTab" class="tabcontent">
        <div id="popup" class="p-5 capture-area">
          <h2>سند صرف</h2>
          <div class="amount-tap-container">
            <div class="amount-tap-content mb-20">
              <h4 class="text-gray mb-20 d-inline ml-100" id="copyTextBillNo">
                9999999
              </h4>
              <h1 class="d-inline">MG</h1>
              <hr class="mb-20" />
              <p class="text-gray mb-20">رقم الاشعار</p>
              <h3 class="text-blue" id="copyTextSourceNo">5555555555555</h3>
              <hr class="mb-20" />
              <p class="text-gray">المبلغ</p>
              <h3 class="text-red text-center mb-20" id="copyTextSourceNo">
                20000 ليرة سورية
              </h3>
              <p class="text-gray mb-20 text-center">عشرون ألفا</p>
              <hr class="mb-20" />
              <p class="text-gray">ملاحظات مهمة</p>
              <p>أموال هي وحدة تبادل تستخدم في الاقتصاد للقيام بالمعاملات</p>
            </div>
          </div>
        </div>
        <div class="amount-tap-buttons">
          <button class="print-btn" id="capture-btn">طباعة الـأسعار</button>
          <button class="copy-btn" id="copyButton">نسخ المعلومات</button>
          <button class="download-btn" id="downloadBtn">تحميل كـ صورة</button>
        </div>
      </div>

      <div id="confirmTab" class="tabcontent">
        <h2>Confirm Details</h2>
        <form action="process_payment.php" method="post">
          <!-- Display selected recipient and amount for confirmation -->
          <p>Recipient: <span id="confirmRecipient"></span></p>
          <p>Amount: <span id="confirmAmount"></span></p>

          <!-- Additional fields for confirmation -->
          <label for="message">Message:</label>
          <textarea name="message" id="message" rows="4" cols="50"></textarea>
          <br />

          <input type="submit" value="Send Money" />
        </form>
      </div>
    </div>

    <script>
      // active tab start
      document.getElementById("recipientTab").style.display = "block";
      function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
      }

      const domainSelector = document.getElementById('domainSelector')
      fetch(
        'https://proxy.cors.sh/https://secure.disoft-tech.com:443/Disoft_RestFul_Api/resources/tayba_test/IntCrossingLightAll',
        {
          headers: {
            'x-cors-api-key': 'temp_c9c7584e09e6b3603708e5f7032d8fb4',
          },
        }
      )
        .then((response) => response.json())
        .then((domains) => {
          console.log(domains.INTLISTCROSSINGLIGHTALL)
          domains.INTLISTCROSSINGLIGHTALL.forEach((domain) => {
            console.log(domain)
            const option = document.createElement('option')
            option.value = domain.DB_CROSSING_NAME
            option.text = domain.DB_CROSSING_NAME
            domainSelector.appendChild(option)
          })
        })
        .catch((error) => {
          console.error('Error fetching domain names:', error)
        })

      domainSelector.addEventListener('change', (event) => {
        const selectedDomain = event.target.value
        console.log('Selected domain:', selectedDomain)
      })
      // combobox end
      const copyButton = document.getElementById("copyButton");
      const copyText = document.getElementById("copyTextSourceNo");
      const copyText1 = document.getElementById("copyTextBillNo");

      copyButton.addEventListener("click", () => {
        const textarea = document.createElement("textarea");
        textarea.value = copyText.textContent + copyText1.textContent;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
        alert("Information copied to clipboard! " + "\n" + textarea.value);
        // copyButton.textContent = "تم النسخ!";
      });
      // copy end

      // print start

      document
        .getElementById("capture-btn")
        .addEventListener("click", function () {
          const captureArea = document.querySelector(".capture-area");
          var downloadLink;
          html2canvas(captureArea).then(function (canvas) {
            const imageDataURL = canvas.toDataURL("image/png");
            downloadLink = document.createElement("a");
            downloadLink.classList.add("text-white");
            downloadLink.href = imageDataURL;
            downloadLink.download = "captured_area.png";
            downloadLink.textContent = "Download Captured Area";
            const imageWindow = window.open("", "_blank");
            imageWindow.document.write(
              '<img src="' + imageDataURL + '" alt="Captured Area"><br>'
            );
            downloadLink.style.display = "none";
            imageWindow.document.body.appendChild(downloadLink);
            setTimeout(() => {
              imageWindow.print();
            }, 100);
          });
        });
      // print end

      // save image start
      document
        .getElementById("downloadBtn")
        .addEventListener("click", function () {
          var popup = document.getElementById("popup");
          html2canvas(popup).then(function (canvas) {
            var link = document.createElement("a");
            link.href = canvas.toDataURL("image/png");
            link.download = "bill_info.png";
            link.click();
          });
        });
      // save image end
    </script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({});
        });
    </script>
</body>
</html>
