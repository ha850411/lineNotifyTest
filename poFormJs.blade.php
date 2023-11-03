<script>
var qs = Qs;
const app = Vue.createApp({
    data() {
        return {
            baseUrl: baseUrl,
            buyAdditional: true,
            empNo: '{{ $staff->emp_no }}',
            empId: '{{ $staff->emp_id }}',
            autoNo: '{{ $autoNo }}',
            custNo: '{{ $custNo }}',
            newCustNo: '{{ $newCustNo }}',
            isCust: '{{ $isCust }}'=='Y' ? true:false,
            isNewCust: '{{ $isNewCust }}'=='Y' ? true:false,
            isHunter: '{{ $isHunter }}'=='Y' ? true:false,
            onlyAdditional: '{{ $onlyAdditional }}'=='Y' ? true:false,
            has360Page: '{{ $has360Page }}'=='Y'? true:false,
            companyId: 0,
            errorMsg: {
                "fileList": {msg:"請上傳文件", display: false},
                "realName": { msg: "請輸入公司真實名稱", display: false },
                "fTitle": { msg: "請輸入發票抬頭", display: false },
                "fName": { msg: "請輸入公司負責人", display: false },
                "preStore": { msg: "請勾選籌備處", display: false },
                // "boss": { msg: "請輸入公司負責人", display: false },
                "fContact": { msg: "請輸入會計聯絡人", display: false },
                "fEmail": { msg: "請輸入電子發票收件Email", display: false },
                "invoice": { msg: "請勾選發票聯式", display: false },
                "invoiceReason": { msg: "請填寫原因", display: false },
                "invoiceAddr": { msg: "invoiceAddr error", display: false },
                "invoiceApply": { msg: "invoiceApply error", display: false },
                "poStartDate": { msg: "poStartDate error", display: false },
                "poTermType": { msg: "poTermType error", display: false },
                "poDiscountType": { msg: "poDiscountType error", display: false },
                "paymentType": { msg: "請選擇付款方式", display: false },
                "disCountReason_4": { msg: "disCountReason_4 error", display: false },
                "disCountReason_5": { msg: "disCountReason_5 error", display: false },
                "attachType": { msg: "", display: false },
                "additionalPackage": { msg: "", display: false },
            },
            // 取得檔案 api 
            attachApiLoading: true,
            attachApiData: [],
            // gcis api
            gcisApiLoading: true,
            // 加值產品 api
            additionalApiLoading: true,
            additionalApiData: [],
            additionalPackageData: {},
            // 加值產品狀態 api
            additionalStatusApiLoading: true,
            additionalStatusApiData: {
                'valid': [],
                'points': [],
            },
            // 加值套餐優惠比例
            additionalDiscountRatios: [],
            // 刊登&優惠資料
            poApiData: {
                'data': [],
                'metadata': [],
            },
            // 刊登套餐 data
            poPackageData: {},
            poApiLoading: true,
            poApiError: "",
            // 同步下載合約
            syncOrderApiLoading: false,
            syncOrderApiData: [],
            previewURL: "",
            selectedAttachType: {
                "id" : "",
                "desc" : "",
            },
            selectedPo: "",
            selectedPoType: "",
            selectedPoPackage: "",
            selectedPoStartDate: "",
            freeAdditionalDisabled: false,
            poCashDiscount: "",
            poDateDiscount: "",
            selectFile: "",
            selectedAddr: {"no": "", "des": ""},
            selectedAdditionalPackage: "", // 目前選擇的加值套餐
            buyAdditionalPackage: false, // 購買加值套餐 checkbox
            // 購買加值產品 - 企業形象
            buyCorporateImage: false, //  checkbox
            selectedCorporateImage: "", // selected
            corporateImageDate: "", // 刊登日 input date
            minCorporateImageDate: "", // 刊登日 input 最小刊登日限制
            // (贈送)加值產品 - 企業形象
            freeCorporateImage: false, // checkbox
            selectedFreeCorporateImage: "", // selected
            freeCorporateImageDate: "", // 刊登日 input date
            minFreeCorporateImageDate: "", // 刊登日 input 最小刊登日限制
            // 購買加值產品 - 白金VIP
            buyPlatinum: false, // checkbox
            selectedPlatinum: "", // 選擇的白金類型 ( 600, 1200...)
            platinumDays: "", // 白金刊期
            platinumStartDate: "", // 刊登日 input date
            minPlatinumStartDate: "", // 刊登日 input 最小刊登日限制
            // (贈送)加值產品 - 白金VIP
            freePlatinum: false, // checkbox
            selectedFreePlatinum: "", // 選擇的白金類型 ( 600, 1200...)
            freePlatinumDays: "", // 白金刊期
            freePlatinumStartDate: "", // 刊登日 input date
            minFreePlatinumStartDate: "", // 刊登日 input 最小刊登日限制
            // 購買加值產品 - 焦點職缺
            buyFocus: false, // checkbox
            inputFocus: "",
            // (贈送)加值產品 - 焦點職缺
            freeFocus: false, // checkbox
            inputFreeFocus: "",
            // 購買加值產品 - 精選工作
            buyPickup: false, // checkbox
            inputPickup: "",
            // (贈送)加值產品 - 精選工作
            freePickup: false, // checkbox
            inputFreePickup: "",
            // 購買加值產品 - 自動排序
            buyJobSort: false, // checkbox 
            inputJobSort: "",
            // (贈送)加值產品 - 自動排序
            freeJobSort: false, // checkbox
            inputFreeJobSort: "",
            // 購買加值產品 - 查詢點數(獵派)
            buyPoints: false, // checkbox
            inputPoints: "",
            // (贈送)加值產品 - 查詢點數(獵派)
            freePoints: false, // checkbox
            inputFreePoints: "", 
            serial: 0,
            // 購物車
            orderData: {}, // 訂單資料
            shoppingCart: { // 購物車資訊
                "size": 0, // 商品數量
                "originPrice": 0, // 所有商品的原價格
                "discount": 0, // 所有商品的總折扣金額
            },
            params: {
                "realName" : '{{ $realName }}',
                "gcisRealName" : '',
                "fTitle" : '{{ $fTitle }}',
                "fName" : '{{ $fName }}',
                "fNameBtn" : '{{ $fNameBtn }}',
                "jobTitle" : '{{ $jobTitle }}',
                "poInvoice" : '{{ $poInvoice }}',
                "boss" : '{{ $boss }}',
                "fContact" : '{{ $fContact }}',
                "fContactBtn" : '{{ $fContactBtn }}',
                "fEmail" : '{{ $fEmail }}',
                "fEmailBtn" : '{{ $fEmailBtn }}',
                "fAddrNo" : '{{ $fAddrNo }}',
                "fAddrNoName" : '{{ $fAddrNoName }}',
                "fAddress" : '{{ $fAddress }}',
                "billtype" : '{{ $billtype }}',
                "realAddrNo" : '{{ $realAddrNo }}',
                "realAddrNoName" : '{{ $realAddrNoName }}',
                "realAddress" : '{{ $realAddress }}',
                "noticeData" : '{{ $noticeData }}',
                "webOrderPendingLink" : '{{ $webOrderPendingLink }}',
                "chinaArrNoCheck" : '{{ $chinaArrNoCheck }}',
                "isPreparatoryCompany" : '{{ $isPreparatoryCompany }}'=='Y' ? true:false,
                "isSendInvoice" : '{{ $isSendInvoice }}'=='Y' ? true:false,
            },
            formParams: {
                "tempFileList": [],
                "deleteTmpFile": true,
                "invoicePaper": false, // 發票聯式 : 紙本
                "invoiceType": "", // 發票聯式: 類型
                "invoiceReason": "", // 發票聯式: 二聯原因
                "delayInvoiceType": "", // 延後開立: 類型 ( 暫不開立 | 開次月 )
                "delayInvoiceReason": "", // 延後開立: 原因
                "delayInvoiceDate": "", // 延後開立: 指定日期
                "delayInvoiceRemark": "", // 延後開立: 發票備註
                "poStartDate": "", // 刊登: 開始刊登日
                "poTermType": "", // 刊登: 刊期
                "poPlanId": "", // 刊期 id
                "poDiscountType": "", // 刊登: 優惠類型
                "poPackage": "", // 刊登: 優惠套餐
                "poCashDiscount": "", // 刊登: 口袋優惠(折扣金額)
                "poDateDiscount": "", // 刊登: 口袋優惠(刊登天數)
                "poFreeType": "", // 刊登: 免費類型 (社福 | 賠償 | 其他)
                "poFreeReason": "", // 刊登: 免費原因(其他)
                "paymentType": "", // 付款方式: (0: 刷卡, 5: 超商, 6: ATM, 4: 其他)
                "additionalInfo": { // 加值產品資訊
                    "imageStartDate": "",
                    "platinumStartDate": "",
                    "freeImageStartDate": "",
                    "minImageStartDate": "", // 企型最小日期
                    "minFreeImageStartDate": "", // 贈送企形最小日期
                    "minPlatinumStartDate": "", // 白金最小日期
                },
                "packageReturnRatio": 0,
                "quotationId": "", // 下載合約 id
                "couponsId": {}, // 做生意優先 - 優惠代碼
            },
            additionalPrice: {
                "corporate-image": {},
                "platinum": {},
                "focus": {},
                "pickup": {},
                "job-sort": {},
                "hunter-resume-point": {},
            },
            additionalKeys: [
                { "key": "corporateImage", "title": "企業形象"},
                { "key": "platinum", "title": "白金"},
                { "key": "focus", "title": "焦點職缺"},
                { "key": "pickup", "title": "精選工作"},
                { "key": "jobSort", "title": "自動排序"},
            ],
            freeAdditionalKeys: [
                { "key": "giftCorporateImage", "title": "企業形象"},
                { "key": "giftFocus", "title": "焦點職缺"},
                { "key": "giftPickup", "title": "精選工作"},
                { "key": "giftJobSort", "title": "自動排序"},
            ],
            additionalMap: {
                "corporateImage": {"title": "企業形象", "unit": "天"},
                "freeCorporateImage": {"title": "企業形象", "unit": "天"},
                "platinum": {"title": "白金VIP", "unit": "天"},
                "freePlatinum": {"title": "白金VIP", "unit": "天"},
                "focus": {"title": "焦點職缺", "unit": "則"},
                "freeFocus": {"title": "焦點職缺", "unit": "則"},
                "pickup": {"title": "精選工作", "unit": "則"},
                "freePickup": {"title": "精選工作", "unit": "則"},
                "jobSort": {"title": "自動排序", "unit": "次"},
                "freeJobSort": {"title": "自動排序", "unit": "次"},
                "points": {"title": "查詢點數", "unit": "點"},
                "freePoints": {"title": "查詢點數", "unit": "點"},
            },
            discountAPI: {},
            discountRemaining: {},
            doSubmitApiLoading: false,
            service_domain: '{{ $service_domain }}',
            getReamingApiLoading: false,
            useContract: false, // 是否套用合約書
            attachTypeMap: {!! $attachTypeMap !!},
            // 做生意優先 - 優惠代碼
            couponsApiData: [],
            couponsApiLoading: false,
            couponsApiErrorMsg: "",
        }
    },
    mounted() {
        this.initFn(); // init function
        // 取得折扣資料
        this.discountAPI = new DiscountAPI(this.empId);
        this.discountAPI.getDiscountData();
        // 非輸入加值單
        if(this.onlyAdditional) {
            this.buyAdditional = true;
            this.getLastPoAndDiscountRecord();
        } else {
            this.getPoData(); // 取得 po 資料
        }
    },
    watch: {
        selectedAttachType: {
            handler: function(nowVal) {
                this.$refs.attachFile.disabled = false;
                if(nowVal.id == 20) {
                    this.selectedAttachType.desc = this.$refs.otherDesc.value;
                }
                if(nowVal.id != "20") {
                    let id = nowVal.id;
                    this.selectedAttachType.desc = this.attachTypeMap[id];
                }
                if(this.selectedAttachType.id == "" || (this.selectedAttachType.id == "20" && this.selectedAttachType.desc == "")) {
                    this.$refs.attachFile.disabled = true;
                }
            },
            deep: true,
        },
        params: {
            handler: function(newVal, oldVal) {
                console.log(newVal, oldVal);
            },
            deep: true,
        },
        mappingFormParams: {
            handler: function(newData, oldData) {
                // 發票聯式切換為二聯時將"紙本"打勾
                if(oldData.invoiceType != newData.invoiceType && newData.invoiceType == "2") {
                    this.formParams.invoicePaper = true;
                }
                console.log(oldData, newData);
                this.setAdditionalPackage();
            },
            deep: true,
        },
        mappingErrorMsg: {
            handler: function(newVal, oldVal) {
                console.log(newVal, oldVal);
            },
            deep: true,
        },
        selectedPo: {
            handler: function() {
                $('[data-toggle="popover"]').popover('hide');
                this.$nextTick(() => {
                    $('[data-toggle="popover"]').popover(); // 活動詳情 init
                });
                this.poCashDiscount = ""; // 口袋優惠 - 清空折扣金額
                this.poDateDiscount = ""; // 口袋優惠 - 清空折扣天數
                // 取得優惠折扣
                this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo);
                // 重置選取的優惠方案
                if(this.isHunter) {
                    this.selectedPoType = "5";
                } else {
                    this.selectedPoType = "";
                }
                this.selectedPoPackage = "";
                console.log('test2');
                // 清空購物車
                if(this.orderData["po"] !== undefined) {
                    delete this.orderData["po"];
                }
                // formParams
                this.formParams.poTermType = this.selectedPo;
                this.formParams.poDiscountType = "";
                if(this.poApiData['data'][this.selectedPo] !== undefined) {
                    this.formParams.poPlanId = this.poApiData['data'][this.selectedPo].id;
                }
                // 取得 VIP 優惠折扣 api
                this.getCouponsData();
            },
        },
        selectFile: {
            handler: function() {
                if(this.selectFile == "") {
                    this.previewURL = "";
                    this.$refs.previewText.style.display = "block";
                } else {
                    this.$refs.previewText.style.display = "none";
                    this.previewURL = this.selectFile;
                }
            }
        },
        selectedAddr: {
            handler: function(newVal, oldVal) {
                this.formParams.fAddrNo = this.selectedAddr.no;
            },
            deep: true,
        },
        buyAdditional: {
            handler: function(){
                if(this.buyAdditional || this.onlyAdditional) {
                    this.getAdditionalDiscountRatios();
                    this.getAdditionalProducts();
                    this.getAdditionalStatus();
                } else {
                    // 移除加值產品購物車清單
                    this.removeAdditional();
                    if(this.orderData["additionalPackage"] !== undefined) {
                        delete this.orderData["additionalPackage"];
                    }
                }
            },
            immediate: true,
        },
        selectedPoType: {
            handler: function() {
                this.addPoHandler();
                // 無優惠 -> 折扣清空
                if(this.selectedPoType == "1") {
                    this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, "", "");
                }
                if(this.selectedPoType != 3) {
                    this.poCashDiscount = "";
                    this.poDateDiscount = "";
                }
            },
        },
        selectedPoPackage: {
            handler: function() {
                this.selectedPoPackageHandler();
            }
        },
        selectedAdditionalPackage: {
            handler: function() {
                // reset
                this.formParams.additionalInfo.imageStartDate = "";
                this.formParams.additionalInfo.platinumStartDate = "";
                this.formParams.additionalInfo.freeImageStartDate = "";
                
                // 購物車
                this.setAdditionalPackage();
            }
        },
        orderData: {
            handler: function() {
                this.shoppingCart.size = 0;
                this.shoppingCart.originPrice = 0;
                this.shoppingCart.discount = 0;
                for (const key in this.orderData) {
                    if(key == "po" || key == "additionalPackage") {
                        this.shoppingCart.size ++;
                        if(!isNaN(parseInt(this.orderData[key].originPrice))) {
                            this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + parseInt(this.orderData[key].originPrice);
                        }
                        if(!isNaN(parseInt(this.orderData[key].cashDiscount))) {
                            this.shoppingCart.discount = parseInt(this.shoppingCart.discount) + parseInt(this.orderData[key].cashDiscount);
                        }
                    } else if(key == "additional") {
                        for (const key2 in this.orderData["additional"]) {
                            this.shoppingCart.size ++;
                            let quantity = 0;
                            switch(key2) {
                                case "corporateImage":
                                    quantity = parseInt(this.orderData["additional"]["corporateImage"].quantity);
                                    if(!isNaN(quantity)) {
                                        this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + parseInt(this.additionalPrice["corporate-image"][quantity].price);
                                    }
                                    break;
                                case "freeCorporateImage":
                                    quantity = parseInt(this.orderData["additional"]["freeCorporateImage"].quantity);
                                    if(!isNaN(quantity)) {
                                        this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + parseInt(this.additionalPrice["corporate-image"][quantity].price);
                                        this.shoppingCart.discount = parseInt(this.shoppingCart.discount) + parseInt(this.additionalPrice["corporate-image"][quantity].price);
                                    }
                                    break;
                                case "platinum":
                                    quantity = parseInt(this.orderData["additional"]["platinum"].quantity);
                                    if(!isNaN(quantity)) {
                                        let unitPrice = parseInt(this.additionalPrice["platinum"][this.selectedPlatinum].price);
                                        let price = unitPrice * quantity;
                                        if(!isNaN(price)) {
                                            this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + price;
                                        }
                                    }
                                    break;
                                case "freePlatinum":
                                    quantity = parseInt(this.orderData["additional"]["freePlatinum"].quantity);
                                    if(!isNaN(quantity)) {
                                        let unitPrice = parseInt(this.additionalPrice["platinum"][this.selectedFreePlatinum].price);
                                        let price = unitPrice * quantity;
                                        if(!isNaN(price)) {
                                            this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + price;
                                            this.shoppingCart.discount = parseInt(this.shoppingCart.discount) + price;
                                        }
                                    }
                                    break;
                                case "focus":
                                case "pickup":
                                case "jobSort":
                                case "points":
                                    quantity = parseInt(this.orderData["additional"][key2].quantity);
                                    if(!isNaN(quantity)) {
                                        let price = parseInt(this.getAdditionalPrice(key2));
                                        if(!isNaN(price)) {
                                            this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + price;
                                        }
                                    }
                                    break;
                                case "freeFocus":
                                case "freePickup":
                                case "freeJobSort":
                                case "freePoints":
                                    quantity = parseInt(this.orderData["additional"][key2].quantity);
                                    if(!isNaN(quantity)) {
                                        let price = parseInt(this.getAdditionalPrice(key2));
                                        if(!isNaN(price)) {
                                            this.shoppingCart.originPrice = parseInt(this.shoppingCart.originPrice) + price;
                                            this.shoppingCart.discount = parseInt(this.shoppingCart.discount) + price;
                                        }
                                    }
                                    break;

                            }
                        }
                    }
                }
            },
            deep: true,
        },
        buyAdditionalPackage: {
            handler: function() {
                if(this.buyAdditionalPackage) {
                    this.removeFreeAdditional();
                }
            }
        },
        freeAdditionalDisabled: {
            handler: function() {
                if(this.freeAdditionalDisabled) {
                    this.removeFreeAdditional();
                }
            }
        }
    },
    computed: {
        mappingErrorMsg: function() {
            return JSON.parse(JSON.stringify(this.errorMsg));
        },
        mappingFormParams: function() {
            return JSON.parse(JSON.stringify(this.formParams));
        },
        // 顯示 po 預計到期日
        showPoEndDate: function() {
            this.formParams.poStartDate = this.selectedPoStartDate;
            if(this.selectedPoStartDate != "" && this.selectedPo != "") {
                let date = new Date(this.selectedPoStartDate);
                if(this.selectedPo != "") {
                    date.setDate(date.getDate() + parseInt(this.selectedPo));
                }
                if(this.poDateDiscount != "" && !isNaN(parseInt(this.poDateDiscount))) {
                    date.setDate(date.getDate() + parseInt(this.poDateDiscount));
                }
                let year = date.getFullYear();
                let month = date.getMonth() + 1; // 月份是從 0 開始的，所以要加 1
                let day = date.getDate();
                return `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            }
            return "";
        },
        // 口袋優惠金額
        showDiscountPoPrice: function() {
            return this.discountRemaining.totalLimit;
        },
        // 口袋優惠折扣金額提示文字
        showDiscountPoCash: function() {
            return this.discountRemaining.cashLimit;
        },
        // 口袋優惠折扣天數提示文字
        showPoDiscountDate: function() {
            return this.discountRemaining.canUseDateDiscount;
        },
        // 半價
        showPoHalfPrice: function() {
            if( this.poApiData.data[this.selectedPo] !== undefined ) {
                return this.formatPrice(Math.floor( this.poApiData.data[this.selectedPo].price/2 ));
            }
            return 0;
        },
        // 顯示套餐贈品價值
        showTotalGiftPrice: function() {
            let total = 0;
            if(this.additionalPackageData[this.selectedAdditionalPackage] !== undefined) {
                for (const key in this.freeAdditionalKeys) {
                    let index = this.freeAdditionalKeys[key].key;
                    let unitPrice = 0;
                    if(this.additionalPackageData[this.selectedAdditionalPackage][index] !== undefined) {
                        let quantity = this.additionalPackageData[this.selectedAdditionalPackage][index].quantity;
                        switch(index) {
                            case "giftCorporateImage":
                                if(this.additionalPrice["corporate-image"][quantity] !== undefined) {
                                    total += parseInt(this.additionalPrice["corporate-image"][quantity].price);
                                }
                                break;
                            case "giftFocus":
                                if(this.additionalPrice["focus"][quantity] !== undefined) {
                                    unitPrice = parseInt(this.additionalPrice["focus"][quantity].price);
                                } else {
                                    unitPrice = parseInt(this.additionalPrice["focus"]["other"].price);
                                }
                                if(!isNaN(unitPrice)) {
                                    total += unitPrice * quantity;
                                }
                                break;
                            case "giftPickup":
                                if(this.additionalPrice["pickup"][quantity] !== undefined) {
                                    unitPrice = parseInt(this.additionalPrice["pickup"][quantity].price);
                                } else {
                                    unitPrice = parseInt(this.additionalPrice["pickup"]["other"].price);
                                }
                                if(!isNaN(unitPrice)) {
                                    total += unitPrice * quantity;
                                }
                                break;
                            case "giftJobSort":
                                if(this.additionalPrice["job-sort"]["other"] !== undefined) {
                                    unitPrice = parseInt(this.additionalPrice["job-sort"]["other"].price);
                                    if(!isNaN(unitPrice)) {
                                        total += unitPrice * quantity;
                                    }
                                }
                                break;
                        }
                    }
                }
            }
            return total;
        },
        // 顯示購物車所有商品
        showTotalProducts: function() {
            let result = {
                "output": "",
                "originPrice": 0,
                "discountPrice": 0,
                "price": 0,
            };
            let data = {
                "po": 0, // 刊登天數
                "corporateImage": 0, // 企形
                "platinum": {}, // 白金
                "focus": 0, // 焦點
                "pickup": 0, // 精選
                "jobSort": 0, // 排序
                "points": 0, // 查詢點數
            };
            let output = [];

            for (const type in this.orderData) {
                if(type == "po") {
                    if(!isNaN(parseInt(this.selectedPo))) {
                        // 一般刊登
                        data.po += parseInt(this.selectedPo);
                        // 口袋優惠贈送天數
                        if(this.poDateDiscount > 0) {
                            data.po += parseInt(this.poDateDiscount);
                        }
                        // 刊登套餐
                        if(this.selectedPoPackage != "" && this.selectedPoType == "2") {
                            if(this.poPackageData[this.selectedPoPackage] !== undefined) {
                                let giftDays = parseInt(this.poPackageData[this.selectedPoPackage].giftPublishedInfo.days);
                                if(!isNaN(giftDays)) {
                                    data.po += giftDays;
                                }
                            }
                            // 加值商品
                        }
                        result.originPrice += parseInt(this.orderData["po"].originPrice);
                        result.discountPrice += parseInt(this.orderData["po"].cashDiscount);
                        result.price+= parseInt(this.orderData["po"].originPrice) -  parseInt(this.orderData["po"].cashDiscount);
                    }
                } else if(type== "additional") {
                    for (const additionalType in this.orderData["additional"]) {
                        let price = parseInt(this.getAdditionalPrice(additionalType));
                        let platinumType = "";
                        result.originPrice += price;
                        switch(additionalType) {
                            case "corporateImage":
                            case "focus":
                            case "pickup":
                            case "jobSort":
                            case "points":
                                data[additionalType] += parseInt(this.orderData["additional"][additionalType].quantity);
                                result.price += price;
                                break;
                            case "platinum":
                                platinumType = this.orderData["additional"]["platinum"]["platinumType"];
                                if(data["platinum"][platinumType] !== undefined) {
                                    data["platinum"][platinumType] += parseInt(this.orderData["additional"]["platinum"].quantity);
                                } else {
                                    data["platinum"][platinumType] = parseInt(this.orderData["additional"]["platinum"].quantity);
                                }
                                result.price += price;
                                break;
                            case "freeCorporateImage":
                                data["corporateImage"] += parseInt(this.orderData["additional"][additionalType].quantity);
                                result.discountPrice += price;
                                break;
                            case "freePlatinum":
                                platinumType = this.orderData["additional"]["freePlatinum"]["platinumType"];
                                if(data["platinum"][platinumType] !== undefined) {
                                    data["platinum"][platinumType] += parseInt(this.orderData["additional"]["freePlatinum"].quantity);
                                } else {
                                    data["platinum"][platinumType] = parseInt(this.orderData["additional"]["freePlatinum"].quantity);
                                }
                                result.discountPrice += price;
                                break;
                            case "freeFocus":
                                data["focus"] += parseInt(this.orderData["additional"][additionalType].quantity);
                                result.discountPrice += price;
                                break;
                            case "freePickup":
                                data["pickup"] += parseInt(this.orderData["additional"][additionalType].quantity);
                                result.discountPrice += price;
                                break;
                            case "freeJobSort":
                                data["jobSort"] += parseInt(this.orderData["additional"][additionalType].quantity);
                                result.discountPrice += price;
                                break;
                            case "freePoints":
                                data["points"] += parseInt(this.orderData["additional"][additionalType].quantity);
                                result.discountPrice += price;
                                break;
                        }
                    }
                } else if(type== "additionalPackage") {
                    result.originPrice += parseInt(this.orderData["additionalPackage"].originPrice);
                    result.discountPrice += parseInt(this.orderData["additionalPackage"].cashDiscount);
                    result.price += parseInt(this.orderData["additionalPackage"].originPrice) - parseInt(this.orderData["additionalPackage"].cashDiscount);
                    let selectedPackageId = this.orderData["additionalPackage"].packageId;
                    if(this.additionalPackageData[selectedPackageId] !== undefined) {
                        // 企形
                        if(this.additionalPackageData[selectedPackageId]["corporateImage"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["corporateImage"].quantity > 0) {
                            data.corporateImage += parseInt(this.additionalPackageData[selectedPackageId]["corporateImage"].quantity);
                        }
                        if(this.additionalPackageData[selectedPackageId]["giftCorporateImage"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["giftCorporateImage"].quantity > 0) {
                            data.corporateImage += parseInt(this.additionalPackageData[selectedPackageId]["giftCorporateImage"].quantity);
                        }
                        // 白金
                        if(this.additionalPackageData[selectedPackageId]["platinum"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["platinum"].quantity > 0) {
                            let platinumType = this.additionalPackageData[selectedPackageId]["platinum"].platinumName;
                            if(data["platinum"][platinumType] !== undefined) {
                                data["platinum"][platinumType] += parseInt(this.additionalPackageData[selectedPackageId]["platinum"].quantity);
                            } else {
                                data["platinum"][platinumType] = parseInt(this.additionalPackageData[selectedPackageId]["platinum"].quantity);
                            }
                        }
                        // 焦點
                        if(this.additionalPackageData[selectedPackageId]["focus"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["focus"].quantity > 0) {
                            data.focus += parseInt(this.additionalPackageData[selectedPackageId]["focus"].quantity);
                        }
                        if(this.additionalPackageData[selectedPackageId]["giftFocus"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["giftFocus"].quantity > 0) {
                            data.focus += parseInt(this.additionalPackageData[selectedPackageId]["giftFocus"].quantity);
                        }
                        // 精選
                        if(this.additionalPackageData[selectedPackageId]["pickup"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["pickup"].quantity > 0) {
                            data.pickup += parseInt(this.additionalPackageData[selectedPackageId]["pickup"].quantity);
                        }
                        if(this.additionalPackageData[selectedPackageId]["giftPickup"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["giftPickup"].quantity > 0) {
                            data.pickup += parseInt(this.additionalPackageData[selectedPackageId]["giftPickup"].quantity);
                        }
                        // 排序
                        if(this.additionalPackageData[selectedPackageId]["jobSort"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["jobSort"].quantity > 0) {
                            data.jobSort += parseInt(this.additionalPackageData[selectedPackageId]["jobSort"].quantity);
                        }
                        if(this.additionalPackageData[selectedPackageId]["giftJobSort"] !== undefined && 
                        this.additionalPackageData[selectedPackageId]["giftJobSort"].quantity > 0) {
                            data.jobSort += parseInt(this.additionalPackageData[selectedPackageId]["giftJobSort"].quantity);
                        }
                    }
                }
            }
            if(data.po > 0) {
                output.push("刊登" + data.po + "天");
            }
            if(data.corporateImage > 0) {
                output.push("企業形象" + data.corporateImage + "天");
            }
            if(Object.keys(data.platinum).length > 0) {
                for (const key in data.platinum) {
                    output.push("白金VIP("+key+")" + data.platinum[key] + "天");
                }
            }
            if(data.focus > 0) {
                output.push("焦點職缺" + data.focus + "則");
            }
            if(data.pickup > 0) {
                output.push("精選工作" + data.pickup + "則");
            }
            if(data.jobSort > 0) {
                output.push("自動排序" + data.jobSort + "次");
            }
            if(data.points > 0) {
                output.push("查詢點數" + data.points + "點");
            }
            if(output.length > 0) {
                output = output.join("、");
            } else {
                output = "";
            }
            result.output = output;
            return result;
        },
        showDiscountOverTip: function() {
            // 取得折扣上限
            let discountLimit = this.showAdditionalItem("calc").total;
            if(discountLimit > 0) {
                // 取得已使用折扣
                useDiscount = this.showTotalProducts.discountPrice;
                if(useDiscount > discountLimit) {
                    return "提醒您，已超過折扣上限！最多折扣 " + this.formatPrice(discountLimit) + " 元";
                }
            }
            return "";
        },
        // 顯示優惠代碼內容
        showCouponsDetail: function() {
            let output = '';
            if(this.formParams.couponsId?.id) {
                if(this.formParams.couponsId?.minimum > 0) {
                    output += `<li>滿`+ this.formParams.couponsId?.minimum + "折" + this.formParams.couponsId?.discount + `元</li>`;
                } else {
                    output += `<li>折` + this.formParams.couponsId?.discount + `元</li>`;
                }
                if(this.formParams.couponsId?.vipUsageLimit > 0 && this.formParams.couponsId?.vipRemainingUsage > 0) {
                    output += `<li>VIP網進限量`+ this.formParams.couponsId?.vipUsageLimit +`組，剩`+ this.formParams.couponsId?.vipRemainingUsage +`組</li>`;
                }
            }
            return output;
        },
    },
    methods: {
        // 初始化 function
        initFn: function() {
            if(this.isCust) {
                this.companyId = this.custNo;
            } else {
                this.companyId = this.newCustNo;
            }
            this.selectedAddr.no = this.params.fAddrNo;
            this.selectedAddr.des = this.params.fAddrNoName;
            this.formParams.realName = this.params.realName;
            this.formParams.fTitle = this.params.fTitle;
            this.formParams.fName = this.params.fName;
            this.formParams.jobTitle = this.params.jobTitle;
            this.formParams.poInvoice = this.params.poInvoice;
            this.formParams.isPreparatoryCompany = this.params.isPreparatoryCompany;
            this.formParams.boss = this.params.boss;
            this.formParams.fContact = this.params.fContact;
            this.formParams.fEmail = this.params.fEmail;
            this.formParams.fAddrNo = this.params.fAddrNo;
            this.formParams.fAddress = this.params.fAddress;
            // 加值產品刊登起始日
            this.minCorporateImageDate = this.smartDate("", 0);
            this.getAttachData(); // 取得檔案清單
            this.getGcisData(); // 取的經濟部公司資料
        },
        // 取得檔案列表
        getAttachData: async function() {
            this.attachApiLoading = true;
            try {
                const response = await axios.get('api/attachFile/getTempAttachFile?companyId=' + this.companyId);
                this.attachApiData = response.data.data;
            } catch (error) {
                if (error.response !== undefined) {
                    this.attachApiError = error.response.data.message;
                } else {
                    this.attachApiError = error.message;
                }
                console.log(error);
            }
            this.attachApiLoading = false;
            this.$nextTick(function () {
                if(this.attachApiData.length > 0) {
                    this.selectFile = baseUrl + this.attachApiData[0].fInfo.fShowDetailLinkByOpen;
                }
            });
        },
        // 經濟部公司資料 api
        getGcisData: async function() {
            if(this.isNewCust) {
                return;
            }
            this.gcisApiLoading = true;
            try {
                const response = await axios.get('api/po/getGcisData',{
                    params: {
                        'invoice': this.params.poInvoice,
                    }
                });
                console.log(response.data);
                if(response.data.data.Company_Name !== undefined && response.data.data.Company_Name != this.params.realName) {
                    this.params.gcisRealName = response.data.data.Company_Name;
                }
            } catch (error) {
                console.log(error);
            }
            this.gcisApiLoading = false;
        },
        // 取得刊期資料 + 套餐
        getPoData: async function(){
            this.poApiLoading = true;
            try {
                const response = await axios.get('api/po/getPoData',{
                    params: {
                        'custNo': this.custNo,
                        'newCustNo': this.newCustNo,
                        'isHunter': (this.isHunter)?"Y":"N",
                    }
                });
                this.poApiData = response.data;
                for (const key1 in this.poApiData.data) {
                    for (const key2 in this.poApiData.data[key1].packages) {
                        let id = this.poApiData.data[key1].packages[key2].id;
                        this.poPackageData[id] = this.poApiData.data[key1].packages[key2];
                    }
                }
                // 開始刊登日期
                this.selectedPoStartDate = this.poApiData.metadata.startDate;
                // 獵派
                if(this.isHunter) {
                    this.selectedPo = 365;
                }
            } catch (error) {
                if (error.response !== undefined) {
                    this.poApiError = error.response.data.message;
                } else {
                    this.poApiError = error.message;
                }
                console.log(error);
            }
            this.poApiLoading = false;
        },
        // 開啟 104 地址選擇器
        toggleCategoryPicker: function() {
            window.categoryPicker.open({
                onSubmit: this.CategoryHandler,
                dataSource: 'Area',
                theme: 'vip-theme',
                selectedItems: [
                   this.selectedAddr
                ],
                maxSelectedNumber: 1,
                expandSelectedData: true,
                backdropClose: true,
                recommendation: false,
                whitelist: '6001[0-9]{6}' // 排除台灣以外的地區
            });
        },
        // 接收 104 地址選擇器 value
        CategoryHandler: function(event) {
            switch (event.type) {
                case 'submit':
                    if(event.selectedItems.length > 0) {
                        this.selectedAddr = event.selectedItems[0];
                    } else {
                        this.selectedAddr = {"no": "", "des": ""};
                    }
                    break;
            }
        },
        // 刪除檔案
        delAttach: async function(fName) {
            this.attachApiLoading = true;
            try {
                const response = await axios.post('api/attachFile/deleteFile', {
                    'companyId': this.companyId,
                    'fileName': fName,
                });
                console.log(response.data);
            } catch (error) {
                console.log(error);
                alert('刪除失敗');
            }
            this.getAttachData(); // 重新讀取檔案列表
        },
        // 上傳檔案
        uploadFile: async function() {
            this.errorMsg.attachType.msg = "";
            this.errorMsg.attachType.display = false;
            if(this.$refs.attachFile.files[0] === undefined) {
                return;
            }
            let fileName = this.$refs.attachFile.files[0].name;
            let fileNameSlice = fileName.split('.');
            let ext = fileNameSlice[(fileNameSlice.length - 1)];
            let acceptFileType = ['doc', 'docx', 'jpg', 'gif', 'tif', 'tiff', 'pdf', 'eml', 'msg'];
            if(acceptFileType.indexOf(ext) === -1) {
                this.errorMsg.attachType.msg = "檔案類型不符，請上傳 .doc .docx .jpg .gif .tif .pdf .eml .msg 檔案";
                this.errorMsg.attachType.display = true;
                return;
            }
            if(this.$refs.attachFile.files[0].size !== undefined) {
                if(this.$refs.attachFile.files[0].size/(1024*1024) > 2) {
                    this.errorMsg.attachType.msg = "檔案大小超過2MB，請重新上傳檔案，謝謝。";
                    this.errorMsg.attachType.display = true;
                    return;
                }
            }
            this.attachApiLoading = true;
            try {
                var formData = new FormData();
                formData.append("companyId", this.companyId);
                formData.append("uploadTempFile", this.$refs.attachFile.files[0]);
                formData.append("fileType", this.selectedAttachType.id);
                formData.append("fileName", this.selectedAttachType.desc);
                formData.append("empNo", this.empNo);
                const response = await axios.post('api/attachFile/uploadFile', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.getAttachData(); // 重新讀取檔案列表
            } catch (error) {
                console.log(error);
                alert('上傳失敗');
                this.attachApiLoading = false;
            }
        },
        // 按鈕控制
        btnFunc: function(action) {
            switch(action) {
                case 'addr':
                    this.selectedAddr.no = this.params.realAddrNo;
                    this.selectedAddr.des = this.params.realAddrNoName;
                    this.formParams.fAddress = this.params.realAddress;
                    break;
            }
        },
        // 控制發票延後開立
        delayInvoiceHandler: function(type) {
            this.formParams.delayInvoiceType = type;
            this.formParams.delayInvoiceReason = "";
            if(this.$refs.delayInvoiceType_3.checked === false && this.$refs.delayInvoiceType_5.checked === false) {
                this.formParams.delayInvoiceType = "";
                this.formParams.delayInvoiceDate = "";
            }
            this.$refs.type3_reason.disabled = false;
            this.$refs.type5_reason.disabled = false;
            this.$refs.delayInvoiceDate.disabled = false;
            switch(type) {
                case '3':
                    if(this.$refs.delayInvoiceType_3.checked) {
                        this.$refs.delayInvoiceType_5.checked = false;
                        this.$refs.type5_reason.value = "";
                        this.$refs.type3_reason.disabled = false;
                        this.$refs.type5_reason.disabled = true;
                        this.$refs.delayInvoiceDate.disabled = false;
                    }
                    break;
                case '5':
                    if(this.$refs.delayInvoiceType_5.checked) {
                        this.$refs.delayInvoiceType_3.checked = false;
                        this.$refs.type3_reason.value = "";
                        this.formParams.delayInvoiceDate = "";
                        this.$refs.type3_reason.disabled = true;
                        this.$refs.delayInvoiceDate.disabled = true;
                        this.$refs.type5_reason.disabled = false;
                    }
                    break;
            }
        },
        // 同步下載合約
        syncOrder: async function() {
            if(this.useContract) {
                // 解除合約
                this.unsetContract();
            } else {
                this.syncOrderApiLoading = true;
                let originHTML = this.$refs.syncOrderBtn.innerHTML;
                this.$refs.syncOrderBtn.innerHTML = '<i class="fa fa-spinner fa-spin fa-fw"></i>';
                try {
                    const response = await axios.get('api/po/orderQuotation',{
                        params: {
                            'custno': this.custNo,
                            'newCustno': this.newCustNo,
                        }
                    });
                    this.syncOrderApiData = response.data.data;
                } catch (error) {
                    console.log(error);
                }
                this.$refs.syncOrderBtn.innerHTML = originHTML;
                this.syncOrderApiLoading = false;
                let hasDiscount = false; // 是否有給予折扣
                // 設定合約 id
                if(this.syncOrderApiData.recordId != "0") {
                    this.useContract = true;
                    this.formParams.quotationId = this.syncOrderApiData.recordId;
                    this.$refs.syncOrderBtn.innerHTML = "解除合約";
                }
                // 設定合約內容
                if( Object.keys(this.syncOrderApiData.products).length > 0 ) {
                    // 刊登方案
                    let termType = this.syncOrderApiData.products.termtype;
                    // 套餐 id
                    let poPackageId = this.syncOrderApiData.products.poPackageContentPk;
                    // 口袋優惠 - 折扣金額
                    let priceDiscount = parseInt(this.syncOrderApiData.products.priceDiscount);
                    console.log('poPackageId', poPackageId);
    
                    if(termType != "" && this.poApiData.data[termType] !== undefined) {
                        // 刊登日
                        if(this.syncOrderApiData.products.startDate != "") {
                            this.selectedPoStartDate = this.smartDate(this.syncOrderApiData.products.startDate, '-');
                        }
                        // 設定刊期
                        this.selectedPo = termType;
                        // 刊登套餐
                        if(this.poPackageData[poPackageId] !== undefined) {
                            hasDiscount = true;
                            this.$nextTick(() => {
                                this.selectedPoPackage = poPackageId;
                                this.selectedPoPackageHandler();
                            });
                        } else {
                            // 口袋優惠 - 折扣金額
                            if(!isNaN(priceDiscount) && priceDiscount > 0) {
                                hasDiscount = true;
                                this.$nextTick(() => {
                                    this.selectedPoType = 3;
                                    this.poCashDiscount = priceDiscount;
                                });
                            }
                            // 口袋優惠 - 贈送天數
                            if( Object.keys(this.syncOrderApiData.giveaway).length > 0 ) {
                                let giftDays = parseInt(this.syncOrderApiData.giveaway.freeAdDays);
                                if(!isNaN(giftDays) && giftDays > 0) {
                                    hasDiscount = true;
                                    this.$nextTick(() => {
                                        this.selectedPoType = 3;
                                        this.poDateDiscount = giftDays;
                                    });
                                }
                            }
                        }
                        if(!hasDiscount) {
                            this.$nextTick(() => {
                                this.selectedPoType = 1;
                            });
                        }
                        // ==== 加值產品 ====
                        // 企型
                        let corporateImage = parseInt(this.syncOrderApiData.products.poType1);
                        if(!isNaN(corporateImage) && corporateImage > 0) {
                            this.buyAdditional = true;
                            this.buyCorporateImage = true;
                            this.selectedCorporateImage = corporateImage;
                            this.additionalHandler('corporateImage'); // 加到購物車
                        }
                        // 焦點
                        let focus = parseInt(this.syncOrderApiData.products.poType9);
                        if(!isNaN(focus) && focus > 0) {
                            this.buyAdditional = true;
                            this.buyFocus = true;
                            this.inputFocus = focus;
                            this.additionalHandler('focus'); // 加到購物車
                        }
                        // 精選
                        let pickup = parseInt(this.syncOrderApiData.products.poType11);
                        if(!isNaN(pickup) && pickup > 0) {
                            this.buyAdditional = true;
                            this.buyPickup = true;
                            this.inputPickup = pickup;
                            this.additionalHandler('pickup'); // 加到購物車
                        }
                        // 自動排序
                        let jobSort = parseInt(this.syncOrderApiData.products.poType23);
                        if(!isNaN(jobSort) && jobSort > 0) {
                            this.buyJobSort = true;
                            this.inputJobSort = jobSort;
                            this.additionalHandler('jobSort'); // 加到購物車
                        }
                        // 付款方式
                        this.formParams.paymentType = this.syncOrderApiData.products.payment;
                    } else {
                        alert('合約沒有選擇[刊期]');
                    }
                }
            }
            
        },
        // 顯示數字轉千分位
        formatPrice: function(price) {
            let intPrice = parseInt(price);
            if(!isNaN(intPrice)) {
                return intPrice.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',');
            }
            return price;
        },
        // 控制口袋優惠 - 折扣金額
        poCashDiscountHandler: function() {
            let cashDiscountValue = parseInt(this.poCashDiscount);
            this.poDateDiscount = ""; // 設定折扣金額時, 清空優惠天數 input
            if(!isNaN(cashDiscountValue)) {
                if(cashDiscountValue > this.discountRemaining.cashLimit) {
                    this.poCashDiscount = this.discountRemaining.cashLimit;
                } else if(cashDiscountValue <= 0) {
                    this.poCashDiscount = "";
                }
            } else {
                this.poCashDiscount = ""; // 輸入的值不為數字 -> 清空
            }
            // 取得優惠折扣
            this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, this.poCashDiscount);
            // 更新購物車
            if(this.selectedPoType == 3 && this.orderData["po"] !== undefined) {
                this.orderData["po"].cashDiscount = this.poCashDiscount;
            }
            // formParams
            this.formParams.poCashDiscount = this.poCashDiscount;
            this.formParams.poDateDiscount = "";
        },
        // 控制口袋優惠 - 折扣天數
        poDateDiscountHandler: function() {
            // 取得優惠折扣(無目前輸入的值)
            this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, this.poCashDiscount, "");
            let cashDiscountValue = parseInt(this.poDateDiscount);
            if(!isNaN(cashDiscountValue)) {
                if(cashDiscountValue > this.discountRemaining.canUseDateDiscount) {
                    this.poDateDiscount = this.discountRemaining.canUseDateDiscount;
                } else if(cashDiscountValue <= 0) {
                    this.poDateDiscount = "";
                }
            } else {
                this.poDateDiscount = ""; // 輸入的值不為數字 -> 清空
            }
            // 取得優惠折扣(目前輸入的值)
            this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, this.poCashDiscount, this.poDateDiscount);
            // 更新購物車
            if(this.selectedPoType == 3 && this.orderData["po"] !== undefined) {
                this.orderData["po"].dateDiscount = this.poDateDiscount;
            }
            // formParams
            this.formParams.poDateDiscount = this.poDateDiscount;
        },
        // 日期操作
        smartDate: function(initDate, addDate, separator) {
            let date = "";
            if(initDate == "") {
                date = new Date();
            } else {
                date = new Date(initDate);
            }
            // 分隔符號
            if(separator == "" || separator===undefined) {
                separator = "-";
            }
            addDate = parseInt(addDate);
            if(isNaN(addDate)) {
                addDate = 0;
            }
            date.setDate(date.getDate() + addDate);
            let year = date.getFullYear();
            let month = date.getMonth() + 1; // 月份是從 0 開始的，所以要加 1
            let day = date.getDate();
            return `${year}${separator}${month.toString().padStart(2, '0')}${separator}${day.toString().padStart(2, '0')}`;
        },
        // 新增po商品到購物車
        addPoHandler: function() {
            // formParams
            this.formParams.poCashDiscount = "";
            this.formParams.poDateDiscount = "";
            let acceptType = [1, 3, 4, 5, 6];
            if(acceptType.indexOf(parseInt(this.selectedPoType)) !== -1) {
                this.freeAdditionalDisabled = false;
                this.selectedPoPackage = "";
                this.formParams.poDiscountType = this.selectedPoType;
                this.formParams.poPackage = "";
                if(this.poApiData.data[this.selectedPo] !== undefined) {
                    let price = 0;
                    price = this.poApiData.data[this.selectedPo].price;
                    let orderData = {
                        "productName": "刊期 " + this.selectedPo + " 天",
                        "originPrice": price,
                        "cashDiscount": 0,
                        "dateDiscount": 0,
                        "detail": [],
                    };
                    // 口袋
                    if(this.selectedPoType == "3") {
                        if(this.poCashDiscount != "") {
                            orderData["cashDiscount"] = this.poCashDiscount;
                            this.formParams.poCashDiscount = this.poCashDiscount;
                        }
                        if(this.poCashDiscount != "") {
                            orderData["dateDiscount"] = this.poDateDiscount;
                            this.formParams.poDateDiscount = this.poDateDiscount;
                        }
                        this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, this.poCashDiscount, this.poDateDiscount);
                    }
                    // 半價
                    if(this.selectedPoType == "4") {
                        orderData["cashDiscount"] = Math.floor(price/2);
                        this.formParams.poCashDiscount = Math.floor(price/2);
                        this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, Math.floor(price/2), "");
                    }
                    // 免費
                    if(this.selectedPoType == "5") {
                        orderData["cashDiscount"] = price;
                        this.freeAdditionalDisabled = true;
                        this.removeFreeAdditional(); // 移除免費贈送加值單品
                        this.discountRemaining = this.discountAPI.getRemaining(this.selectedPo, price, "");
                        this.formParams.poCashDiscount = price;
                    }
                    // 優惠代碼
                    if(this.selectedPoType == "6") {
                        let couponsDiscount = this.formParams.couponsId?.discount || 0;
                        orderData["cashDiscount"] = couponsDiscount;
                        this.formParams.poCashDiscount = couponsDiscount;
                    }
                    this.orderData["po"] = orderData;
                }
            } else {
                if(this.poPackageData[this.selectedPoPackage] !== undefined) {
                    this.selectedPoType = 2;
                    this.formParams.poDiscountType = 2;
                    this.formParams.poPackage = this.selectedPoPackage;
                    let orderData = {
                        "productName": this.poPackageData[this.selectedPoPackage].name,
                        "originPrice": this.poPackageData[this.selectedPoPackage].originalPrice,
                        "cashDiscount": this.poPackageData[this.selectedPoPackage].originalPrice-this.poPackageData[this.selectedPoPackage].price,
                        "dateDiscount": 0,
                        "detail": this.poPackageData[this.selectedPoPackage].detail,
                    };
                    this.orderData["po"] = orderData;
                }
            }
        },
        // 取得加值 data
        getAdditionalProducts: async function() {
            this.additionalApiLoading = true;
            try {
                const response = await axios.get('api/po/getAdditionalProducts',{
                    params: {
                        'custNo': this.custNo,
                    }
                });
                this.additionalApiData = response.data;

                // 處理加值 package
                if(this.additionalApiData.data.package !== undefined && this.additionalApiData.data.package.length > 0) {
                    for (const key in this.additionalApiData.data.package) {
                        let pkg_id = this.additionalApiData.data.package[key].id;
                        this.additionalPackageData[pkg_id] = this.additionalApiData.data.package[key];
                    }
                }
                // 處理加值一般產品
                if(this.additionalApiData.data.standard !== undefined) {
                    for (const key in this.additionalApiData.data.standard) {
                        let type = this.additionalApiData.data.standard[key].type;
                        if(type == "corporate-image") {
                            for (const key2 in this.additionalApiData.data.standard[key].priceInterval) {
                                let max = this.additionalApiData.data.standard[key].priceInterval[key2].max;
                                this.additionalPrice["corporate-image"][max] = {
                                    "price": this.additionalApiData.data.standard[key].priceInterval[key2].price,
                                };
                            }
                        }  else if(type == "platinum") {
                            // 白金類型
                            let platinumName = this.additionalApiData.data.standard[key].platinumName;
                            for (const key2 in this.additionalApiData.data.standard[key].priceInterval) {
                                let max = this.additionalApiData.data.standard[key].priceInterval[key2].max;
                                this.additionalPrice["platinum"][platinumName] = {
                                    "max": max,
                                    "price": this.additionalApiData.data.standard[key].priceInterval[key2].price,
                                };
                            }
                        } else if(type == "focus" || type == "pickup" || type == "job-sort" || type == "hunter-resume-point") {
                            for (const key2 in this.additionalApiData.data.standard[key].priceInterval) {
                                let min = this.additionalApiData.data.standard[key].priceInterval[key2].min;
                                let max = this.additionalApiData.data.standard[key].priceInterval[key2].max;
                                let price = this.additionalApiData.data.standard[key].priceInterval[key2].price;
                                if(max === null) {
                                    this.additionalPrice[type]["other"] = {
                                        "price": price,
                                    };
                                } else {
                                    for(i=min; i<=max; i ++) {
                                        this.additionalPrice[type][i] = {
                                            "price": this.additionalApiData.data.standard[key].priceInterval[key2].price,
                                        };
                                    }
                                }
                            }
                        }
                    }
                }
                // 設定加值起始日期
                this.minDateHandler("init-image");
                this.minDateHandler("init-platinum");
                console.log(response.data);
            } catch (error) {
                console.log(error);
            }
            this.additionalApiLoading = false;
        },
        // 加值套餐新增到購物車
        setAdditionalPackage: function() {
            if(this.orderData["additionalPackage"] !== undefined) {
                delete this.orderData["additionalPackage"];
            }
            if(this.buyAdditionalPackage && this.selectedAdditionalPackage != "") {
                this.orderData["additionalPackage"] = {
                    "packageId": this.selectedAdditionalPackage,
                    "imageStartDate": this.formParams.additionalInfo.imageStartDate,
                    "platinumStartDate": this.formParams.additionalInfo.platinumStartDate,
                    "freeImageStartDate": this.formParams.additionalInfo.freeImageStartDate,
                    "originPrice": this.additionalPackageData[this.selectedAdditionalPackage].originalPrice, 
                    "cashDiscount": this.additionalPackageData[this.selectedAdditionalPackage].originalPrice - this.additionalPackageData[this.selectedAdditionalPackage].discountPrice,
                };
            }
        },
        // 加值套餐控制
        additionalHandler: function(type) {
            if(this.orderData["additional"] === undefined) {
                this.orderData["additional"] = {};
            }
            this.removeOrderItem(type, false);
            // 取得加值產品 id 
            let additionalIds = {};
            for (const key in this.additionalApiData['data']['standard']) {
                let additionalType = this.additionalApiData['data']['standard'][key].type;
                if(additionalType == "platinum") {
                    let platinumName = this.additionalApiData['data']['standard'][key].platinumName;
                    if(additionalIds['platinum']===undefined) {
                        additionalIds['platinum'] = {};
                    }
                    additionalIds[additionalType][platinumName] = {
                        "id": this.additionalApiData['data']['standard'][key].id,
                    };
                } else {
                    additionalIds[additionalType] = {"id": this.additionalApiData['data']['standard'][key].id};
                }
            }

            switch(type) {
                case "corporateImage": // 企型(付費)
                    if(this.buyCorporateImage && parseInt(this.selectedCorporateImage) > 0 && this.corporateImageDate!="") {
                        this.orderData["additional"]["corporateImage"] = {
                            "name": "corporateImage",
                            "id": additionalIds['corporate-image'].id,
                            "quantity": this.selectedCorporateImage,
                            "startDate": this.corporateImageDate,
                        };
                    }
                    // 企形日期操作
                    this.minDateHandler('image-buy', true);
                    break;
                case "freeCorporateImage": // 企型(免費)
                    if(this.freeCorporateImage && parseInt(this.selectedFreeCorporateImage) > 0 && this.freeCorporateImageDate!="") {
                        this.orderData["additional"]["freeCorporateImage"] = {
                            "name": "freeCorporateImage",
                            "id": additionalIds['corporate-image'].id,
                            "quantity": this.selectedFreeCorporateImage,
                            "startDate": this.freeCorporateImageDate,
                        };
                    }
                    break;
                case "platinum": // 白金(付費)
                    if(this.buyPlatinum && this.selectedPlatinum!="" && this.platinumDays!="" && this.platinumStartDate != "") {
                        let platinumId = 0;
                        if(additionalIds['platinum'][this.selectedPlatinum] !== undefined) {
                            platinumId = additionalIds['platinum'][this.selectedPlatinum].id;
                        }
                        this.orderData["additional"]["platinum"] = {
                            "name": "platinum",
                            "id": platinumId,
                            "platinumType": this.selectedPlatinum,
                            "quantity": this.platinumDays,
                            "startDate": this.platinumStartDate,
                        };
                    }
                    // 白金日期操作
                    this.minDateHandler('platinum-buy', true);
                    break;
                case "freePlatinum": // 白金(免費)
                    if(this.freePlatinum && this.selectedFreePlatinum!="" && this.freePlatinumDays!="" && this.freePlatinumStartDate != "") {
                        let platinumId = 0;
                        if(additionalIds['platinum'][this.selectedFreePlatinum] !== undefined) {
                            platinumId = additionalIds['platinum'][this.selectedFreePlatinum].id;
                        }
                        this.orderData["additional"]["freePlatinum"] = {
                            "name": "freePlatinum",
                            "id": platinumId,
                            "platinumType": this.selectedFreePlatinum,
                            "quantity": this.freePlatinumDays,
                            "startDate": this.freePlatinumStartDate,
                        };
                    }
                    break;
                case "focus": // 焦點(付費)
                    if(this.buyFocus && parseInt(this.inputFocus) > 0) {
                        if(isNaN(parseInt(this.inputFocus))) {
                            this.inputFocus = "";
                        } else {
                            this.orderData["additional"]["focus"] = {
                                "name": "focus",
                                "id": additionalIds['focus'].id,
                                "quantity": parseInt(this.inputFocus),
                            };
                        }
                    }
                    break;
                case "freeFocus": // 焦點(免費)
                    if(this.freeFocus && parseInt(this.inputFreeFocus) > 0) {
                        if(isNaN(parseInt(this.inputFreeFocus))) {
                            this.inputFreeFocus = "";
                        } else {
                            this.orderData["additional"]["freeFocus"] = {
                                "name": "freeFocus",
                                "id": additionalIds['focus'].id,
                                "quantity": parseInt(this.inputFreeFocus),
                            };
                        }
                    }
                    break;
                case "pickup": // 精選(付費)
                    if(this.buyPickup && parseInt(this.inputPickup) > 0) {
                        if(isNaN(parseInt(this.inputPickup))) {
                            this.inputPickup = "";
                        } else {
                            this.orderData["additional"]["pickup"] = {
                                "name": "pickup",
                                "id": additionalIds['pickup'].id,
                                "quantity": parseInt(this.inputPickup),
                            };
                        }
                    }
                    break;
                case "freePickup": // 精選(免費)
                    if(this.freePickup && parseInt(this.inputFreePickup) > 0) {
                        if(isNaN(parseInt(this.inputFreePickup))) {
                            this.inputFreePickup = "";
                        } else {
                            this.orderData["additional"]["freePickup"] = {
                                "name": "freePickup",
                                "id": additionalIds['pickup'].id,
                                "quantity": parseInt(this.inputFreePickup),
                            };
                        }
                    }
                    break;
                case "jobSort": // 自動排序(付費)
                    if(this.buyJobSort && parseInt(this.inputJobSort) > 0) {
                        if(isNaN(parseInt(this.inputJobSort))) {
                            this.inputJobSort = "";
                        } else {
                            this.orderData["additional"]["jobSort"] = {
                                "name": "jobSort",
                                "id": additionalIds['job-sort'].id,
                                "quantity": parseInt(this.inputJobSort),
                            };
                        }
                    }
                    break;
                case "freeJobSort": // 自動排序(免費)
                    if(this.freeJobSort && parseInt(this.inputFreeJobSort) > 0) {
                        if(isNaN(parseInt(this.inputFreeJobSort))) {
                            this.inputFreeJobSort = "";
                        } else {
                            this.orderData["additional"]["freeJobSort"] = {
                                "name": "freeJobSort",
                                "id": additionalIds['job-sort'].id,
                                "quantity": parseInt(this.inputFreeJobSort),
                            };
                        }
                    }
                    break;
                case "points": // 查詢點數(付費)
                    if(this.buyPoints && parseInt(this.inputPoints) > 0) {
                        if(isNaN(parseInt(this.inputPoints))) {
                            this.inputPoints = "";
                        } else {
                            this.orderData["additional"]["points"] = {
                                "name": "points",
                                "id": additionalIds['hunter-resume-point'].id,
                                "quantity": parseInt(this.inputPoints),
                            };
                        }
                    }
                    break;
                case "freePoints": // 查詢點數(免費)
                    if(this.freePoints && parseInt(this.inputFreePoints) > 0) {
                        if(isNaN(parseInt(this.inputFreePoints))) {
                            this.inputFreePoints = "";
                        } else {
                            this.orderData["additional"]["freePoints"] = {
                                "name": "freePoints",
                                "id": additionalIds['hunter-resume-point'].id,
                                "quantity": parseInt(this.inputFreePoints),
                            };
                        }
                    }
                    break;
            }
            // 刊登套餐若有買加值單品則可再贈送加值單品
            if(this.selectedPoType == "2" || this.selectedPoType == "5") {
                this.freeAdditionalDisabled = true;
                // 取得加值產品回饋金額
                let AdditionalDiscountQuota = this.getDiscountQuota();
                if(AdditionalDiscountQuota > 0) {
                    this.freeAdditionalDisabled = false;
                }
            } else {
                this.freeAdditionalDisabled = false;
            }
        },
        // 顯示加值產品價格
        getAdditionalPrice: function(type) {
            let quantity = 0;
            let price = "";
            switch(type) {
                case "corporateImage":
                case "freeCorporateImage":
                    if(type == "corporateImage") {
                        quantity = this.selectedCorporateImage;
                    } else {
                        quantity = this.selectedFreeCorporateImage;
                    }
                    if(this.additionalPrice["corporate-image"][quantity] !== undefined) {
                        price = this.additionalPrice["corporate-image"][quantity].price;
                    }
                    break;
                case "platinum":
                case "freePlatinum":
                    if(type == "platinum") {
                        if(this.selectedPlatinum != "" && this.platinumDays!="") {
                            let unitPrice = parseInt(this.additionalPrice.platinum[this.selectedPlatinum].price);
                            if(!isNaN(unitPrice)) {
                                price = unitPrice * parseInt(this.platinumDays);
                            }
                        }
                    } else {
                        if(this.selectedFreePlatinum != "" && this.freePlatinumDays!="") {
                            let unitPrice = parseInt(this.additionalPrice.platinum[this.selectedFreePlatinum].price);
                            if(!isNaN(unitPrice)) {
                                price = unitPrice * parseInt(this.freePlatinumDays);
                            }
                        }
                    }
                    break;
                case "focus":
                    quantity = parseInt(this.inputFocus);
                    if(!isNaN(quantity)) {
                        if(this.additionalPrice["focus"][quantity] === undefined) {
                            price = this.additionalPrice["focus"]["other"].price * quantity;
                        } else {
                            price = this.additionalPrice["focus"][quantity].price * quantity;
                        }
                    }
                    break;
                case "freeFocus": // 免費贈送用原價計算
                    quantity = parseInt(this.inputFreeFocus);
                    if(!isNaN(quantity)) {
                        if(this.additionalPrice["focus"][1] !== undefined) {
                            price = this.additionalPrice["focus"][1].price * quantity;
                        } else {
                            price = this.additionalPrice["focus"]["other"].price * quantity;
                        }
                    }
                    break;
                case "pickup":
                    quantity = parseInt(this.inputPickup);
                    if(!isNaN(quantity)) {
                        if(this.additionalPrice["pickup"][quantity] === undefined) {
                            price = this.additionalPrice["pickup"]["other"].price * quantity;
                        } else {
                            price = this.additionalPrice["pickup"][quantity].price * quantity;
                        }
                    }
                    break;
                case "freePickup":
                    quantity = parseInt(this.inputFreePickup);
                    if(!isNaN(quantity)) {
                        if(this.additionalPrice["pickup"]["1"] !== undefined) {
                            price = this.additionalPrice["pickup"]["1"].price * quantity;
                        } else {
                            price = this.additionalPrice["pickup"]["other"].price * quantity;
                        }
                    }
                    break;
                case "jobSort":
                case "freeJobSort":
                    if(type == "jobSort") {
                        quantity = parseInt(this.inputJobSort);
                    } else {
                        quantity = parseInt(this.inputFreeJobSort);
                    }
                    if(!isNaN(quantity)) {
                        price = this.additionalPrice["job-sort"]["other"].price * quantity;
                    }
                    break;
                case "points":
                case "freePoints":
                    if(type == "points") {
                        quantity = parseInt(this.inputPoints);
                    } else {
                        quantity = parseInt(this.inputFreePoints);
                    }
                    if(!isNaN(quantity)) {
                        price = this.additionalPrice["hunter-resume-point"]["other"].price * quantity;
                    }
                    break;
            }
            return price;
        },
        removeOrderItem: function(type, reset) {
            if(type == "po") {
                if(this.orderData["po"] !== undefined) {
                    this.selectedPo = "";
                    this.selectedPoType = "";
                    this.selectedPoPackage = "";
                    delete this.orderData["po"];
                }
            } else if(type == "additionalPackage") {
                if(this.orderData["additionalPackage"] !== undefined) {
                    this.selectedAdditionalPackage = "";
                    this.buyAdditionalPackage = false;
                    delete this.orderData["additionalPackage"];
                }
            } else {
                if(this.orderData["additional"] !== undefined && this.orderData["additional"][type] !== undefined) {
                    delete this.orderData["additional"][type];
                }
                if(reset) {
                    switch(type) {
                        case "corporateImage":
                            this.buyCorporateImage = false;
                            this.selectedCorporateImage = "";
                            this.corporateImageDate = "";
                            break;
                        case "freeCorporateImage":
                            this.freeCorporateImage = false;
                            this.selectedFreeCorporateImage = "";
                            this.freeCorporateImageDate = "";
                            break;
                        case "platinum":
                            this.buyPlatinum = false;
                            this.selectedPlatinum = "";
                            this.platinumDays = "";
                            this.platinumStartDate = "";
                            break;
                        case "freePlatinum":
                            this.freePlatinum = false;
                            this.selectedFreePlatinum = "";
                            this.freePlatinumDays = "";
                            this.freePlatinumStartDate = "";
                            break;
                        case "focus":
                            this.buyFocus = false;
                            this.inputFocus = "";
                            break;
                        case "freeFocus":
                            this.freeFocus = false;
                            this.inputFreeFocus = "";
                            break;
                        case "pickup":
                            this.buyPickup = false;
                            this.inputPickup = "";
                            break;
                        case "freePickup":
                            this.freePickup = false;
                            this.inputFreePickup = "";
                            break;
                        case "jobSort":
                            this.buyJobSort = false;
                            this.inputJobSort = "";
                            break;
                        case "freeJobSort":
                            this.freeJobSort = false;
                            this.inputFreeJobSort = "";
                            break;
                        case "points":
                            this.buyPoints = false;
                            this.inputPoints = "";
                            break;
                        case "freePoints":
                            this.freePoints = false;
                            this.inputFreePoints = "";
                            break;
                    }
                }
            }
        },
        showAdditionalItem: function(action) {
            let result = {
                "html": "",
                "total": 0,
            };
            let keys = [];
            let html = [];
            let output = "";
            let total = 0;
            switch(action) {
                case "buy":
                case "free":
                    if(this.orderData["additional"] !== undefined) {
                        if(action == "buy") {
                            keys = ["corporateImage", "platinum", "focus", "pickup", "jobSort", "points"];
                        } else {
                            keys = ["freeCorporateImage", "freePlatinum", "freeFocus", "freePickup", "freeJobSort", "freePoints"];
                        }
                        for (const key in keys) {
                            let index = keys[key];
                            if(this.orderData["additional"][index] !== undefined) {
                                let price = this.getAdditionalPrice(index);
                                total += price;
                                switch(index) {
                                    case "corporateImage":
                                    case "focus":
                                    case "pickup":
                                    case "jobSort":
                                    case "points":
                                    case "freeCorporateImage":
                                    case "freeFocus":
                                    case "freePickup":
                                    case "freeJobSort":
                                    case "freePoints":
                                        output = this.additionalMap[index].title + this.orderData["additional"][index].quantity + this.additionalMap[index].unit;
                                        output +=  this.formatPrice(price) + "元";
                                        html.push(output);
                                        break;
                                    case "platinum":
                                    case "freePlatinum":
                                        output = "白金(" + this.orderData.additional[index].platinumType + ")" + this.orderData.additional[index].quantity + "天";
                                        output += this.formatPrice(price) + '元';
                                        html.push(output);
                                        break;
                                }
                            }
                        }
                        if(html.length > 0) {
                            html = html.join(" + ");
                            if(action == "buy") {
                                html = "付費: " + html + ' = 共' + this.formatPrice(total) + "元";
                            } else {
                                html = "免費: " + html + ' = 共' + this.formatPrice(total) + "元";
                            }
                            result.html = html;
                            result.total = total;
                        }
                    }
                    break;
                case "calc":
                    if(this.buyAdditionalPackage) {
                        return result;
                    }
                    // 購買加值產品總金額
                    let totalPrice = this.showAdditionalItem('buy').total;
                    // 剩餘折扣額度
                    let ratio = this.getRatio(totalPrice);
                    this.formParams.packageReturnRatio = ratio;
                    // 可贈送金額
                    let discountQuota = Math.round(ratio * totalPrice);
                    console.log('discountQuota', discountQuota);
                    // 已贈送金額
                    useDiscount = this.showAdditionalItem("free").total;
                    if(totalPrice > 0 && discountQuota > 0) {
                        let remainingQuota = discountQuota - useDiscount;
                        if(remainingQuota < 0) {
                            html = '<span class="text-red">注意!!免費之金額已超過可贈送金額【'+this.formatPrice(totalPrice) + "x"+ (ratio*100) + "% = " + this.formatPrice(discountQuota) +'元】</span>';
                        } else {
                            html = "可贈送金額: " + this.formatPrice(discountQuota) + "元 - 已贈送金額: " +  this.formatPrice(useDiscount) + "元";
                            html += " = 尚可贈送金額: " + this.formatPrice(remainingQuota);
                        }
                    } else {
                        if(this.discountRemaining.canUseTotalDiscount !== undefined) {
                            if(this.selectedPoType == 2 || this.selectedPoType == 5) {
                                discountQuota = 0;
                            } else {
                                discountQuota = this.discountRemaining.canUseTotalDiscount; // 口袋額度
                            }
                            if(discountQuota > 0 && (totalPrice > 0 || useDiscount > 0)) {
                                let remainingQuota = discountQuota - useDiscount;
                                if(remainingQuota < 0) {
                                    html = '<span class="text-red">注意!!免費之金額已超過可贈送金額【'+this.formatPrice(discountQuota) + '元】</span>';
                                } else {

                                    html = "可贈送金額: " + this.formatPrice(discountQuota) + "元 - 已贈送金額: " +  this.formatPrice(useDiscount) + "元";
                                    html += " = 尚可贈送金額: " + this.formatPrice(remainingQuota);
                                }
                            }
                        }
                    }
                    result.html = html;
                    result.total = discountQuota;
                    break;
            }
            return result;
        },
        // 透過價格取得可折扣 % 數
        getRatio: function(price) {
            let ratio = 0;
            if(this.additionalDiscountRatios.length > 0) {
                for (const key in this.additionalDiscountRatios) {
                    let startPrice = parseInt(this.additionalDiscountRatios[key].startPrice);
                    let endPrice = parseInt(this.additionalDiscountRatios[key].endPrice);
                    if( (price >= startPrice && price <= endPrice) || price >= endPrice ) {
                        if(this.additionalDiscountRatios[key].returnRatio > ratio) {
                            ratio = this.additionalDiscountRatios[key].returnRatio;
                        }
                    }
                }
            }
            if(ratio > 0) {
                ratio = ratio/100;
            }
            return ratio;
        },
        // 加值產品日期連動控制
        minDateHandler: function(action, reset) {
            if(reset===undefined) {
                reset = true;
            }
            let date = "";
            let addDays = 0;
            switch(action) {
                case "init-image":
                    console.log('init-image');
                    // 秒轉毫秒
                    let corporateImageStartDate = this.additionalApiData.metadata.corporateImageAllowedToPurchaseAt * 1000;
                    // 套餐品項 - 企形
                    this.formParams.additionalInfo.minImageStartDate = this.smartDate(corporateImageStartDate);
                    // 套餐贈品 - 企形
                    this.formParams.additionalInfo.minFreeImageStartDate = this.smartDate(corporateImageStartDate);
                    // 付費加值單品 - 企形
                    this.minCorporateImageDate = this.smartDate(corporateImageStartDate);
                    // 免費加值單品 - 企形
                    this.minFreeCorporateImageDate = this.smartDate(corporateImageStartDate);
                    break;
                case "init-platinum":
                    console.log('init-platinum');
                    // 秒轉毫秒
                    let platinumStartDate = this.additionalApiData.metadata.platinumAllowedToPurchaseAt * 1000;
                    // 套餐品項 - 白金
                    this.formParams.additionalInfo.minPlatinumStartDate = this.smartDate(platinumStartDate);
                    // 付費加值單品 - 白金
                    this.minPlatinumStartDate = this.smartDate(platinumStartDate);
                    // 免費加值單品 - 白金
                    this.minFreePlatinumStartDate = this.smartDate(platinumStartDate);
                    break;
                case "image-package": // 設定套餐企形日期後的處理
                    this.minDateHandler('init-image');
                    if(this.additionalPackageData[this.selectedAdditionalPackage] !== undefined) {
                        date = this.formParams.additionalInfo.imageStartDate;
                        addDays = parseInt(this.additionalPackageData[this.selectedAdditionalPackage].corporateImage.quantity) + 1;
                        if(addDays > 0 && date != '') {
                            // 贈送企形日期設定
                            this.formParams.additionalInfo.minFreeImageStartDate = this.smartDate(date, addDays);
                            // 付費企形日期設定
                            this.minCorporateImageDate = this.smartDate(date, addDays);
                            // 免費企形日期設定
                            this.minFreeCorporateImageDate = this.smartDate(date, addDays);
                            if(reset) {
                                this.corporateImageDate = "";
                                this.freeCorporateImageDate = "";
                            }
                        }
                    }
                    break;
                case "image-package-free": // 設定套餐企形(贈送)日期後的處理
                    if(this.additionalPackageData[this.selectedAdditionalPackage] !== undefined) {
                        date = this.formParams.additionalInfo.freeImageStartDate;
                        addDays = parseInt(this.additionalPackageData[this.selectedAdditionalPackage].giftCorporateImage.quantity) + 1;
                        if(addDays > 0 && date!='') {
                            // 付費企形日期設定
                            this.minCorporateImageDate = this.smartDate(date, addDays);
                            // 免費企形日期設定
                            this.minFreeCorporateImageDate = this.smartDate(date, addDays);
                            if(reset) {
                                this.corporateImageDate = "";
                                this.freeCorporateImageDate = "";
                            }
                        } else { // 若天數不大於零或日期為空, 使用上一層的日期計算
                            this.minDateHandler('image-package', false);
                        }
                    }
                    break;
                case "image-buy": // 設定付費企形日期後的處理
                    date = this.corporateImageDate;
                    addDays = parseInt(this.selectedCorporateImage) + 1;
                    if(addDays > 0 && date!='') {
                        // 免費企形日期設定
                        this.minFreeCorporateImageDate = this.smartDate(date, addDays);
                        if(reset) {
                            this.freeCorporateImageDate = "";
                        }
                    } else { // 若天數不大於零或日期為空, 使用上一層的日期計算
                        this.minDateHandler('image-package', false);
                        this.minDateHandler('image-package-free', false);
                    }
                    break;
                case "platinum-package": // 設定套餐白金日期後的處理
                    this.minDateHandler('init-platinum');
                    if(this.additionalPackageData[this.selectedAdditionalPackage] !== undefined) {
                        date = this.formParams.additionalInfo.platinumStartDate;
                        addDays = parseInt(this.additionalPackageData[this.selectedAdditionalPackage].platinum.quantity) + 1;
                        if(addDays > 0 && date!='') {
                            // 付費白金日期設定
                            this.minPlatinumStartDate = this.smartDate(date, addDays);
                            // 免費白金日期設定
                            this.minFreePlatinumStartDate = this.smartDate(date, addDays);
                            if(reset) {
                                this.platinumStartDate = "";
                                this.freePlatinumStartDate = "";
                            }
                        }
                    }
                    break;
                case "platinum-buy":
                    date = this.platinumStartDate;
                    addDays = parseInt(this.platinumDays) + 1;
                    if(addDays > 0 && date!='') {
                        // 免費白金日期設定
                        this.minFreePlatinumStartDate = this.smartDate(date, addDays);
                        if(reset) {
                            this.freePlatinumStartDate = "";
                        }
                    } else {
                        this.minDateHandler('platinum-package', false);
                    }
                    break;
            }
        },
        removeAdditional: function() {
            // 企形清除
            this.removeOrderItem("corporateImage", true);
            // 白金清除
            this.removeOrderItem("platinum", true);
            // 焦點職缺清除
            this.removeOrderItem("focus", true);
            // 精選工作清除
            this.removeOrderItem("pickup", true);
            // 自動排序清除
            this.removeOrderItem("jobSort", true);
            // 查詢點數清除
            this.removeOrderItem("points", true);
            // 免費加值清除
            this.removeFreeAdditional();
        },
        removeFreeAdditional: function() {
            // 免費企形清除
            this.removeOrderItem("freeCorporateImage", true);
            // 免費白金清除
            this.removeOrderItem("freePlatinum", true);
            // 免費焦點職缺清除
            this.removeOrderItem("freeFocus", true);
            // 免費精選工作清除
            this.removeOrderItem("freePickup", true);
            // 免費自動排序清除
            this.removeOrderItem("freeJobSort", true);
            // 免費查詢點數清除
            this.removeOrderItem("freePoints", true);
        },
        // 取得加值套餐優惠比例
        getAdditionalDiscountRatios: async function() {
            try {
                const response = await axios.get('api/po/getCsrDiscountRatios');
                this.additionalDiscountRatios = response.data.data;    
            } catch (error) {
                console.log(error);
            }
        },
        // 取得加值產品狀態
        getAdditionalStatus: async function() {
            this.additionalStatusApiLoading = true;
            try {
                const response = await axios.get('api/po/getAdditionalStatus');
                this.additionalStatusApiData = response.data;
            } catch (error) {
                console.log(error);
            }
            this.additionalStatusApiLoading = false;
        },
        // 送出表單 -> 確認
        preSubmit: function() {
            // 檢查表單
            if(this.checkForm()) {
                $('#submitOrderModal').modal("show");
            }
        },
        // 送出表單 -> 執行
        doSubmit: async function() {
            this.doSubmitApiLoading = true;
            try {
                var formData = new FormData();
                // 代登員編
                formData.append("empId", this.empId);

                formData.append("autoNo", this.autoNo);
                formData.append("custNo", this.custNo);
                formData.append("newCustNo", this.newCustNo);
                formData.append("isHunterDispatch", (this.isHunter)?"1":"");
                
                formData.append("totalUseDiscountPrice", this.formParams.poCashDiscount);
                formData.append("orderType", "");
                formData.append("tempFileList", this.formParams.tempFileList.join(","));
                let tempFileNameList = [];
                for (const key in this.attachApiData) {
                    if(this.formParams.tempFileList.indexOf(this.attachApiData[key].fInfo.fName) !== -1) {
                        tempFileNameList.push(this.attachApiData[key].fTitleName);
                    }
                }
                formData.append("tempFileNameList", tempFileNameList);
                let hasDelTmpFile = (this.formParams.deleteTmpFile)?"1":"";
                formData.append("hasDelTmpFile", hasDelTmpFile);
                // 基本資料
                formData.append("realName", this.formParams.realName);
                formData.append("fTitle", this.formParams.fTitle);
                formData.append("fName", this.formParams.fName);
                formData.append("jobTitle", this.formParams.jobTitle);
                formData.append("poInvoice", this.formParams.poInvoice);
                let preStore = (this.formParams.isPreparatoryCompany)?"1":"";
                formData.append("preStore", preStore); // 籌備處
                formData.append("boss", this.formParams.boss);
                formData.append("fContact", this.formParams.fContact);
                formData.append("fEmail", this.formParams.fEmail);
                formData.append("billtype", this.formParams.invoiceType);
                let billPaper = (this.formParams.invoicePaper)?"1":""; // 發票-紙本
                formData.append("billPaper", billPaper);
                formData.append("billReason", this.formParams.invoiceReason);
                formData.append("fAddrNo", this.formParams.fAddrNo);
                formData.append("fAddress", this.formParams.fAddress);
                formData.append("delayInvoiceType", this.formParams.delayInvoiceType);
                formData.append("delayInvoiceReason", this.formParams.delayInvoiceReason);
                formData.append("delayInvoiceDate", this.formParams.delayInvoiceDate);
                formData.append("delayInvoiceRemark", this.formParams.delayInvoiceRemark);
                // 刊登
                formData.append("quotationId", this.formParams.quotationId); // 合約 id
                formData.append("adSdate", this.formParams.poStartDate);
                formData.append("termtype", this.formParams.poTermType);
                formData.append("poPlanId", this.formParams.poPlanId);
                formData.append("poOriginPrice", this.poApiData?.['data']?.[this.selectedPo]?.price || 0); // 刊期原價
                formData.append("poCashDiscount", this.formParams.poCashDiscount);
                formData.append("poDateDiscount", this.formParams.poDateDiscount);
                // 付款金額
                let payMoney = this.poApiData?.['data']?.[this.selectedPo]?.price || 0;
                payMoney = payMoney - this.formParams.poCashDiscount;

                // 優惠折扣類型
                let priceRemark = "";
                switch(this.formParams.poDiscountType) {
                    case "4": // 半價
                        priceRemark = "1";
                        break;
                    case "5": // 免費
                        priceRemark = "2";
                        break;
                    case "3": // 口袋
                    case "6": // 優惠代碼
                        priceRemark = "3";
                        break;
                }
                formData.append("priceRemark", priceRemark);
                formData.append("poDiscountType", this.formParams.poDiscountType);
                formData.append("poPackage", this.formParams.poPackage);
                if(this.formParams.poPackage !== undefined && this.poPackageData[this.formParams.poPackage] !== undefined) {
                    formData.append("poPackageOriginPrice", this.poPackageData[this.formParams.poPackage].originalPrice);
                    formData.append("poPackagePrice", this.poPackageData[this.formParams.poPackage].price);
                    payMoney = this.poPackageData[this.formParams.poPackage].price;
                }
                formData.append("payMoney", payMoney);

                // 免費
                formData.append("priceReasonFree", this.formParams.poFreeType);
                formData.append("priceReasonFreeOther", this.formParams.poFreeReason);
                formData.append("pay", this.formParams.paymentType);

                // orderData
                formData.append("orderData", JSON.stringify(this.orderData));
                // 加值產品價格
                formData.append("additionalPrice", JSON.stringify(this.additionalPrice));
                // 套餐回饋比例
                formData.append("packageReturnRatio", this.formParams.packageReturnRatio * 100);
                // 純加值
                formData.append("onlyAdditional", this.onlyAdditional?"Y":"N");
                // 優惠代碼
                formData.append("couponsId", JSON.stringify(this.formParams.couponsId));
                
                
                const res = await axios.post("api/po/newInsertPo", formData);
                let data = res.data.data;
                console.log(data);
                if(eoWebBrowser !== undefined && data != "" && data.forward !== undefined) {
                    //判斷不是在CRM 360頁，才執行
                    if(typeof eoWebBrowser !== 'undefined' && !this.has360Page) {
                        try { //addin 做 SR暫存
                            eoWebBrowser.extInvoke("CRM.TRIGGER.EVENT", ["CopySr"]);
                        } catch(err) {
                            alert("找不到元件,"+err.toString());
                        }
            	    }
                    if(data.crmParam.isNewCust == 1) {
                        try {
                            var crmParam = data.crmParam;
                            eoWebBrowser.extInvoke("CRM.UPDATE.ORG", [crmParam.realName, crmParam.invoice, crmParam.autoNo, crmParam.custNo, crmParam.mdmKey, crmParam.boss, crmParam.addrNo, crmParam.address, crmParam.addrNoArea]);
                        } catch(err) {
                            alert("找不到元件,"+err.toString());
                        }
                    }
                    //導頁
                    if(typeof data.forward != "undefined"){
                        setTimeout(function(){
                            //判斷不是在CRM 360頁，才執行
                            if(typeof eoWebBrowser !== 'undefined' && !this.has360Page) {
                                //addin 做 SR暫存
                                try {
                                    eoWebBrowser.extInvoke("CRM.TRIGGER.EVENT", ["PasteSr"]);
                                } catch(err) {
                                    alert("找不到元件,"+err.toString());
                                }
                            }
                            if(typeof eoWebBrowser !== 'undefined') {
                                try {
                                    if(data.crmParam.isNewCust == 1) {
                                        eoWebBrowser.extInvoke("CRM.Trigger.Event", ["create_order_done_new"]);
                                    } else {
                                        eoWebBrowser.extInvoke("CRM.Trigger.Event", ["create_order_done_old"]);
                                    }
                                } catch(err) {
                                    alert("找不到元件,"+err.toString());
                                }
                            } else {
                                window.location = data.forward;
                            }
                        }, 4000);
                    }
                } else {
                    alert("系統錯誤");
                }
            } catch (error) {
                console.log(error);
            }
            this.doSubmitApiLoading = false;
        },
        // 檢查表單資料是否正確
        checkForm: function() {
            // 重置所有 message
            for (const key in this.errorMsg) {
                this.errorMsg[key].display = false;
            }
            // 上傳文件
            if(this.formParams.tempFileList.length == 0) {
                this.errorMsg.fileList.display = true;
                this.$refs.fileList.focus();
                return false;
            }
            // 必填欄位檢查
            let requireColumns = ['realName', 'fTitle', 'fName', 'fContact', 'fEmail'];
            for (const key in requireColumns) {
                let colName = requireColumns[key];
                if(this.formParams[colName] == "") {
                    this.errorMsg[colName].display = true;
                    this.$refs[colName].focus();
                    return false;
                }
            }
            // 發票聯式
            if(this.formParams.invoiceType == "") {
                this.errorMsg.invoice.display = true;
                this.$refs.invoice.focus();
                return false;
            } else if(this.formParams.invoiceType == "2" && this.formParams.invoiceReason == "") {
                this.errorMsg.invoiceReason.display = true;
                this.$refs.invoiceReason.focus();
                return false;
            }
            // 發票郵寄地址 - addrno
            if(this.formParams.fAddrNo == "") {
                this.errorMsg.invoiceAddr.msg = "請選擇發票郵寄地區";
                this.errorMsg.invoiceAddr.display = true;
                this.$refs.invoiceAddr.focus();
                return false;
            }
            // 發票郵寄地址 - 地址
            if(this.formParams.fAddress == "") {
                this.errorMsg.invoiceAddr.msg = "請輸入發票郵寄地址";
                this.errorMsg.invoiceAddr.display = true;
                this.$refs.invoiceAddr.focus();
                return false;
            }

            // 發票延後開立
            if(this.formParams.delayInvoiceType != "") {
                if(this.formParams.delayInvoiceReason == "") {
                    this.errorMsg.invoiceApply.msg = "請輸入原因";
                    this.errorMsg.invoiceApply.display = true;
                    this.$refs.invoiceApply.focus();
                    return false;
                }
                if(this.formParams.delayInvoiceType == "3" && this.formParams.delayInvoiceDate == "") {
                    this.errorMsg.invoiceApply.msg = "請選擇可指定開立日期";
                    this.errorMsg.invoiceApply.display = true;
                    this.$refs.delayInvoiceDate.focus();
                    return false;
                }
            }
            if(!this.onlyAdditional) {
                // 開始刊登日期
                if(this.formParams.poStartDate == "") {
                    this.errorMsg.poStartDate.msg = "請選擇開始刊登日期";
                    this.errorMsg.poStartDate.display = true;
                    this.$refs.poStartDate.focus();
                    return false;
                }
                // 刊登方案
                if(this.formParams.poTermType == "") {
                    this.errorMsg.poTermType.msg = "請選擇刊登方案";
                    this.errorMsg.poTermType.display = true;
                    this.$refs.poTermType.focus();
                    return false;
                }
                // 優惠方案
                if(this.formParams.poDiscountType == "") {
                    this.errorMsg.poDiscountType.msg = "請選擇優惠方案";
                    this.errorMsg.poDiscountType.display = true;
                    this.$refs.poDiscountType.focus();
                    return false;
                }
                // 免費/半價
                if(this.formParams.poDiscountType=='4' || this.formParams.poDiscountType=='5') {
                    let key = 'disCountReason_' + this.formParams.poDiscountType;
                    if(this.formParams.poFreeType == "") {
                        this.errorMsg[key].msg = "請選擇優惠原因";
                        this.errorMsg[key].display = true;
                        this.$refs['poReasonType_1'].focus();
                        return false;
                    } else if(this.formParams.poFreeType == '3' && this.formParams.poFreeReason == "") {
                        this.errorMsg[key].msg = "請輸入優惠原因";
                        this.errorMsg[key].display = true;
                        this.$refs['poFreeReason'].focus();
                        return false;
                    }
                }
            }
            // 加值套餐日期檢查
            if(this.buyAdditionalPackage) {
                if(this.selectedAdditionalPackage !== "") {
                    // 檢查企形
                    if(this.$refs.imageStartDate !== undefined && this.formParams.additionalInfo.imageStartDate == "") {
                        this.errorMsg.additionalPackage.msg = "請選擇企形開始日";
                        this.errorMsg.additionalPackage.display = true;
                        this.$refs.additionalPackage.focus();
                        return false;
                    }
                    // 檢查白金
                    if(this.$refs.platinumStartDate !== undefined && this.formParams.additionalInfo.platinumStartDate == "") {
                        this.errorMsg.additionalPackage.msg = "請選擇白金開始日";
                        this.errorMsg.additionalPackage.display = true;
                        this.$refs.additionalPackage.focus();
                        return false;
                    }
                    // 檢查贈送企形
                    if(this.$refs.freeImageStartDate !== undefined && this.formParams.additionalInfo.freeImageStartDate == "") {
                        this.errorMsg.additionalPackage.msg = "請選擇企形開始日";
                        this.errorMsg.additionalPackage.display = true;
                        this.$refs.additionalPackage.focus();
                        return false;
                    }
                } else {
                    this.errorMsg.additionalPackage.msg = "請選擇加值套餐";
                    this.errorMsg.additionalPackage.display = true;
                    this.$refs.selectedAdditionalPackage.focus();
                    return false;
                }
            }

            // 付款方式
            if(this.formParams.paymentType == "") {
                this.errorMsg.paymentType.display = true;
                this.$refs.paymentType.focus();
                return false;
            }
            return true;
        },
        filePreview: function(link) {
            window.open(link);
        },
        getDiscountQuota: function() {
            // 購買加值產品總金額
            let totalPrice = this.showAdditionalItem('buy').total;
            // 剩餘折扣額度
            let ratio = this.getRatio(totalPrice);
            // 可贈送金額
            let discountQuota = Math.round(ratio * totalPrice);
            return discountQuota;
        },
        getLastPoAndDiscountRecord: async function() {
            this.getReamingApiLoading = true;
            try {
                const response = await this.discountAPI.getLastPoAndDiscountRecord(this.custNo);
                this.discountRemaining = this.discountAPI.getRemaining(response.termType); // 取得折扣
            } catch (error) {
                console.log(error);
            } 
            this.getReamingApiLoading = false;
        },
        selectedPoPackageHandler: function() {
            if(this.selectedPoPackage != '') {
                this.selectedPoType = '';
                this.addPoHandler();
                // 購買刊登套餐時, 若無購買付費套餐則不可贈送加值單品
                if(this.showAdditionalItem('buy').total == 0) {
                    this.freeAdditionalDisabled = true;
                    this.removeFreeAdditional();
                }
            }
        },
        unsetContract: function() {
            this.selectedPo = '';
            this.useContract = false;
            this.formParams.quotationId = "";
            this.formParams.couponsId = {};
            this.$refs.syncOrderBtn.innerHTML = "同步下載合約";
            this.removeAdditional();
        },
        // 取得 VIP 優惠代碼
        getCouponsData: async function() {
            // 目前選擇刊期價格
            let currentPoPrice = this.poApiData?.data[this.selectedPo]?.price;
            if(!currentPoPrice) {
                return;
            }
            this.formParams.couponsId = {};
            this.couponsApiData = [];
            this.couponsApiLoading = true;
            try {
                const response = await axios.get('api/po/coupons/' + this.custNo, {
                    params: {
                        consumePrice: currentPoPrice
                    }
                });
                console.log(response.data.data);
                if(response?.data?.data) {
                    for (const data of response.data.data) {
                        console.log(data);

                        if(data.discount >= this.couponsApiData[0]?.discount) {
                            this.couponsApiData = [data, ...this.couponsApiData];
                        } else {
                            this.couponsApiData.push(data);
                        }
                    }
                }
                if(this.couponsApiData.length > 0) {
                    this.formParams.couponsId = this.couponsApiData[0];
                }
            } catch (error) {
                console.log(error);
            }
            this.couponsApiLoading = false;
        },
    },
});
const vm = app.mount('#app');
</script>
