<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Dashboard_Model extends Model {
    
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function getDataSalesByTypeModel() {
        $query = "SELECT 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
            NVL(TG.ID, 0) AS ITEM_CATEGORY_ID,
            CASE WHEN TG.ID IS NULL THEN 'Бусад'
            ELSE TG.NAME END AS ITEM_CATEGORY_NAME,
            SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            (SELECT  
            MAX(HDR.INVOICE_DATE)-1  AS INVOICE_DATE,
            ITM.ITEM_CATEGORY_ID,  
            SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT 
            FROM SM_SALES_INVOICE_HEADER HDR 
            INNER JOIN 
            (SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            SM_SALES_INVOICE_DETAIL DTL
            WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
            GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
            INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID 
            WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
            AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1510117117628
            GROUP BY ITM.ITEM_CATEGORY_ID) LINE
            INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
            LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
            LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
            GROUP BY 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD'),
            TG.ID,
            TG.NAME
            ORDER BY SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }

    public function getDataSalesFtByTypeModel() {
        $query = "SELECT
        TO_CHAR(LINE.INVOICE_DATE,'YYYY-MM-DD') AS INVOICE_DATE,
        NVL(TG.ID,0) AS ITEM_CATEGORY_ID,
        CASE
          WHEN TG.ID IS NULL THEN 'Бусад'
          ELSE TG.NAME
         END
        AS ITEM_CATEGORY_NAME,
        SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
       FROM
        (
         SELECT
          MAX(HDR.INVOICE_DATE) - 1 AS INVOICE_DATE,
          ITM.ITEM_CATEGORY_ID,
          SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT
         FROM
          SM_SALES_INVOICE_HEADER HDR
          INNER JOIN (
           SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
           FROM
            SM_SALES_INVOICE_DETAIL DTL
           WHERE
            DTL.IS_REMOVED = 0
            OR   DTL.IS_REMOVED IS NULL
           GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID
          ) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
          INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID
         WHERE
          TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
          AND   (
           HDR.IS_REMOVED = 0
           OR    HDR.IS_REMOVED IS NULL
          )
          AND   HDR.STORE_ID = 1525063362607
         GROUP BY
          ITM.ITEM_CATEGORY_ID
        ) LINE
        INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
        LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
        LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
       GROUP BY
        TO_CHAR(LINE.INVOICE_DATE,'YYYY-MM-DD'),
        TG.ID,
        TG.NAME
       ORDER BY
        SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }
    public function getDataSalesFtByJuiseModel() {
        $query = "SELECT
        TO_CHAR(LINE.INVOICE_DATE,'YYYY-MM-DD') AS INVOICE_DATE,
        NVL(TG.ID,0) AS ITEM_CATEGORY_ID,
        CASE
          WHEN TG.ID IS NULL THEN 'Бусад'
          ELSE TG.NAME
         END
        AS ITEM_CATEGORY_NAME,
        SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
       FROM
        (
         SELECT
          MAX(HDR.INVOICE_DATE) - 1 AS INVOICE_DATE,
          ITM.ITEM_CATEGORY_ID,
          SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT
         FROM
          SM_SALES_INVOICE_HEADER HDR
          INNER JOIN (
           SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
           FROM
            SM_SALES_INVOICE_DETAIL DTL
           WHERE
            DTL.IS_REMOVED = 0
            OR   DTL.IS_REMOVED IS NULL
           GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID
          ) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
          INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID
         WHERE
          TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
          AND   (
           HDR.IS_REMOVED = 0
           OR    HDR.IS_REMOVED IS NULL
          )
          AND   HDR.STORE_ID = 1525063362348
         GROUP BY
          ITM.ITEM_CATEGORY_ID
        ) LINE
        INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
        LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
        LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
       WHERE
        MP.ID != 69
       GROUP BY
        TO_CHAR(LINE.INVOICE_DATE,'YYYY-MM-DD'),
        TG.ID,
        TG.NAME
       ORDER BY
        SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }
    public function getDataSalesFtByShangrilaModel() {
        $query = "SELECT
        TO_CHAR(LINE.INVOICE_DATE,'YYYY-MM-DD') AS INVOICE_DATE,
        NVL(TG.ID,0) AS ITEM_CATEGORY_ID,
        CASE
          WHEN TG.ID IS NULL THEN 'Бусад'
          ELSE TG.NAME
         END
        AS ITEM_CATEGORY_NAME,
        SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
       FROM
        (
         SELECT
          MAX(HDR.INVOICE_DATE) - 1 AS INVOICE_DATE,
          ITM.ITEM_CATEGORY_ID,
          SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT
         FROM
          SM_SALES_INVOICE_HEADER HDR
          INNER JOIN (
           SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
           FROM
            SM_SALES_INVOICE_DETAIL DTL
           WHERE
            DTL.IS_REMOVED = 0
            OR   DTL.IS_REMOVED IS NULL
           GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID
          ) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
          INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID
         WHERE
          TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
          AND   (
           HDR.IS_REMOVED = 0
           OR    HDR.IS_REMOVED IS NULL
          )
          AND   HDR.STORE_ID = 1565061062132
         GROUP BY
          ITM.ITEM_CATEGORY_ID
        ) LINE
        INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
        LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
        LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
       WHERE
        MP.ID != 69
       GROUP BY
        TO_CHAR(LINE.INVOICE_DATE,'YYYY-MM-DD'),
        TG.ID,
        TG.NAME
       ORDER BY
        SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }

    public function getDataRSalesByTypeModel() {
        $query = "SELECT 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
            NVL(TG.ID, 0) AS ITEM_CATEGORY_ID,
            CASE WHEN TG.ID IS NULL THEN 'Бусад'
            ELSE TG.NAME END AS ITEM_CATEGORY_NAME,
            SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            (SELECT  
            MAX(HDR.INVOICE_DATE)-1  AS INVOICE_DATE,
            ITM.ITEM_CATEGORY_ID,  
            SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT 
            FROM SM_SALES_INVOICE_HEADER HDR 
            INNER JOIN 
            (SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            SM_SALES_INVOICE_DETAIL DTL
            WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
            GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
            INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID 
            WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
            AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1525063362348
            GROUP BY ITM.ITEM_CATEGORY_ID) LINE
            INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
            LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
            LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
            GROUP BY 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD'),
            TG.ID,
            TG.NAME
            ORDER BY SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }

    public function getDataR2SalesByTypeModel() {
        $query = "SELECT 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
            NVL(TG.ID, 0) AS ITEM_CATEGORY_ID,
            CASE WHEN TG.ID IS NULL THEN 'Бусад'
            ELSE TG.NAME END AS ITEM_CATEGORY_NAME,
            SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            (SELECT  
            MAX(HDR.INVOICE_DATE)-1  AS INVOICE_DATE,
            ITM.ITEM_CATEGORY_ID,  
            SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT 
            FROM SM_SALES_INVOICE_HEADER HDR 
            INNER JOIN 
            (SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            SM_SALES_INVOICE_DETAIL DTL
            WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
            GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
            INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID 
            WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
            AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1525063362607
            GROUP BY ITM.ITEM_CATEGORY_ID) LINE
            INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
            LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
            LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
            GROUP BY 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD'),
            TG.ID,
            TG.NAME
            ORDER BY SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }
    
    public function getDataSalesStoreByTypeModel() {
        $query = "SELECT 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
            NVL(TG.ID, 0) AS ITEM_CATEGORY_ID,
            CASE WHEN TG.ID IS NULL THEN 'Бусад'
            ELSE TG.NAME END AS ITEM_CATEGORY_NAME,
            SUM(LINE.TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            (SELECT  
            MAX(HDR.INVOICE_DATE)-1  AS INVOICE_DATE,
            ITM.ITEM_CATEGORY_ID,  
            SUM(DTL.TOTAL_AMOUNT) AS TOTAL_AMOUNT 
            FROM SM_SALES_INVOICE_HEADER HDR 
            INNER JOIN 
            (SELECT
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID,
            SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
            FROM
            SM_SALES_INVOICE_DETAIL DTL
            WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
            GROUP BY
            DTL.SALES_INVOICE_ID,
            DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
            INNER JOIN IM_ITEM ITM ON DTL.PRODUCT_ID = ITM.ITEM_ID 
            WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
            AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID = 1510117118100
            GROUP BY ITM.ITEM_CATEGORY_ID) LINE
            INNER JOIN IM_ITEM_CATEGORY CTR ON CTR.ITEM_CATEGORY_ID = LINE.ITEM_CATEGORY_ID
            LEFT JOIN META_TAG_MAP MP ON LINE.ITEM_CATEGORY_ID = MP.INVOICE_ID
            LEFT JOIN META_TAG TG ON MP.TAG_ID = TG.ID
            GROUP BY 
            TO_CHAR(LINE.INVOICE_DATE, 'YYYY-MM-DD'),
            TG.ID,
            TG.NAME
            ORDER BY SUM(LINE.TOTAL_AMOUNT) DESC";
        
        return $this->db->GetAll($query);
    }

    public function getDataSalesListModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1522805082602265',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
    }

    public function getDataSalesFtListModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1561960420429',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
    }
    
    public function getPaymentJuiceBarSaleModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1568962552792',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
    }
    public function getPaymentShangrilaSaleModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1571730684362',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
    }

    public function getDataRSalesListModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1526289771752',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
    }

    public function getDataR2SalesListModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1526289771764',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
    }

    public function getDataSalesStoreListModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1522946896152',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;        
    }

    public function getDataSalesByActivityModel() {
        $query = "SELECT 
                    HDR.INVOICE_DATE,
                    SUM(HDR.TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
                    GROUP BY
                    DTL.SALES_INVOICE_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TO_CHAR(HDR.invoice_date, 'YYYYMM') = TO_CHAR(SYSDATE - 1, 'YYYYMM') AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL)
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID = 1510117117628
                    GROUP BY HDR.INVOICE_DATE
                    ORDER BY HDR.INVOICE_DATE";

        return $this->db->GetAll($query);
    }

    public function getDataRSalesByActivityModel() {
        $query = "SELECT 
                    HDR.INVOICE_DATE,
                    SUM(HDR.TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
                    GROUP BY
                    DTL.SALES_INVOICE_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TO_CHAR(HDR.invoice_date, 'YYYYMM') = TO_CHAR(SYSDATE - 1, 'YYYYMM') AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL)
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID = 1525063362348
                    GROUP BY HDR.INVOICE_DATE
                    ORDER BY HDR.INVOICE_DATE";

        return $this->db->GetAll($query);
    }

    public function getDataR2SalesByActivityModel() {
        $query = "SELECT 
                    HDR.INVOICE_DATE,
                    SUM(HDR.TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
                    GROUP BY
                    DTL.SALES_INVOICE_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TO_CHAR(HDR.invoice_date, 'YYYYMM') = TO_CHAR(SYSDATE - 1, 'YYYYMM') AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL)
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID = 1525063362607
                    GROUP BY HDR.INVOICE_DATE
                    ORDER BY HDR.INVOICE_DATE";

        return $this->db->GetAll($query);
    }

    public function getDataSalesStoreByActivityModel() {
        $query = "SELECT 
                    HDR.INVOICE_DATE,
                    SUM(HDR.TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL
                    GROUP BY
                    DTL.SALES_INVOICE_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TO_CHAR(HDR.invoice_date, 'YYYYMM') = TO_CHAR(SYSDATE - 1, 'YYYYMM') AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL)
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID = 1510117118100
                    GROUP BY HDR.INVOICE_DATE
                    ORDER BY HDR.INVOICE_DATE";

        return $this->db->GetAll($query);
    }

    public function getDataSupplyByActivityModel() {
        $query = "SELECT 
                    BOOK_DATE,
                    SUM(UNIT_AMOUNT) AS UNIT_AMOUNT
                    FROM (
                    SELECT
                    TRUNC(B.BOOK_DATE) AS BOOK_DATE,
                    SUM(BD.FACT_4) UNIT_AMOUNT
                    FROM
                    IC_INVOICE_BOOK B
                    INNER JOIN IC_INVOICE_BOOK_DTL BD ON BD.INVOICE_BOOK_ID = B.ID
                    LEFT JOIN CRM_CUSTOMER C          ON C.CUSTOMER_ID = B.DIM_1
                    LEFT JOIN IM_ITEM II              ON BD.DIM_1 = II.ITEM_ID
                    LEFT JOIN META_DM_RECORD_MAP MP   ON MP.SRC_RECORD_ID = BD.ID AND MP.SRC_TABLE_NAME = 'IC_INVOICE_BOOK_DTL' AND MP.TRG_TABLE_NAME = 'IM_ITEM_BOOK'
                    WHERE
                    B.BOOK_TYPE_ID = 40000008 AND
                    B.BOOK_DATE > SYSDATE - 30 AND
                    BD.WFM_STATUS_ID = 1506563637022636
                    GROUP BY
                    TRUNC(B.BOOK_DATE)
                    UNION
                    SELECT
                    TRUNC(SYSDATE - ROWNUM) BOOK_DATE,
                    0 UNIT_AMOUNT
                    FROM
                    DUAL
                    CONNECT BY ROWNUM < 31
                    )
                    GROUP BY BOOK_DATE
                    ORDER BY BOOK_DATE";

        return $this->db->GetAll($query);
    }
    
    public function getDataIndustryByActivityModel() {
        $query = "WITH
                    DATES AS
                    (
                     SELECT
                      (ADD_MONTHS(TO_DATE(TO_CHAR(SYSDATE,'YYYY')||'-01-01','YYYY-MM-DD'), - 1) + (LEVEL - 1)) AS DATES
                     FROM
                      DUAL
                      CONNECT BY LEVEL <= (
                      CASE
                       WHEN (ADD_MONTHS(TO_DATE(TO_CHAR(SYSDATE,'YYYY')||'-'||TO_CHAR(SYSDATE,'MM')||'-01','YYYY-MM-DD'), 1)) < SYSDATE
                       THEN (ADD_MONTHS(TO_DATE(TO_CHAR(SYSDATE,'YYYY')||'-'||TO_CHAR(SYSDATE,'MM')||'-01','YYYY-MM-DD'), 1))
                       ELSE SYSDATE + 1
                      END - ADD_MONTHS(TO_DATE(TO_CHAR(SYSDATE,'YYYY')||'-01-01','YYYY-MM-DD'), - 1) )
                    )
                    ,
                    SKK AS
                    (
                     SELECT
                      K.ITEM_ID,
                      K.STORE_KEEPER_KEY_ID,
                      BOOK.BOOK_DATE,
                      SUM(NVL(DTL.IN_COST_AMOUNT,0) - NVL(DTL.OUT_COST_AMOUNT, 0)) / SUM(NVL(DTL.IN_QTY,0) - NVL(DTL.OUT_QTY,0)) AS UNIT_COST
                     FROM
                      IM_ITEM_BOOK BOOK
                     INNER JOIN IM_ITEM_BOOK_DTL DTL   ON BOOK.ITEM_BOOK_ID = DTL.ITEM_BOOK_ID
                     INNER JOIN IM_ITEM_KEY K          ON DTL.ITEM_KEY_ID = K.ITEM_KEY_ID
                     INNER JOIN IM_STORE_KEEPER_KEY SK ON K.STORE_KEEPER_KEY_ID = SK.STORE_KEEPER_KEY_ID
                     GROUP BY
                      K.ITEM_ID,
                      K.STORE_KEEPER_KEY_ID,
                      BOOK.BOOK_DATE
                     HAVING
                      SUM(NVL(DTL.IN_QTY,0) - NVL(DTL.OUT_QTY,0)) <> 0
                    )
                    ,
                    ITEMS AS
                    (
                     SELECT
                      ITEM_ID
                     FROM
                      SKK
                     GROUP BY
                      ITEM_ID
                    )
                    ,
                    ITEM_DATES AS
                    (
                     SELECT
                      ITEMS.ITEM_ID,
                      DATES
                     FROM
                      DATES,
                      ITEMS
                    )
                    ,
                    T AS
                    (
                     SELECT
                      D.ITEM_ID,
                      D.DATES AS BOOK_DATE,
                      S.STORE_KEEPER_KEY_ID,
                      S.UNIT_COST
                     FROM
                      ITEM_DATES D
                     LEFT JOIN SKK S ON D.DATES = S.BOOK_DATE AND D.ITEM_ID = S.ITEM_ID
                     ORDER BY
                      D.ITEM_ID,
                      S.STORE_KEEPER_KEY_ID,
                      D.DATES
                    )
                    ,
                    T1 AS
                    (
                     SELECT
                      T.ITEM_ID,
                      T.BOOK_DATE,
                      T.STORE_KEEPER_KEY_ID                                                                                                             AS SOURCE_STORE_KEEPER_KEY_ID,
                      T.UNIT_COST                                                                                                                       AS SOURCE_UNIT_COST,
                      MAX(STORE_KEEPER_KEY_ID) OVER (PARTITION BY ITEM_ID ORDER BY ITEM_ID, BOOK_DATE ROWS BETWEEN UNBOUNDED PRECEDING AND 0 FOLLOWING) AS STORE_KEEPER_KEY_ID,
                      MAX(UNIT_COST) OVER (PARTITION BY ITEM_ID ORDER BY ITEM_ID, BOOK_DATE ROWS BETWEEN UNBOUNDED PRECEDING AND 0 FOLLOWING)           AS UNIT_COST
                     FROM
                      T
                     ORDER BY
                      T.ITEM_ID,
                      T.BOOK_DATE
                    )
                    ,
                    T2 AS
                    (
                     SELECT
                      P.PRODUCT_ID,
                      P.PRODUCT_NAME,
                      TO_CHAR(T1.BOOK_DATE,'YYYY')  AS P_YEAR,
                      TO_CHAR(T1.BOOK_DATE,'MM')    AS P_MONTH,
                      TO_CHAR(T1.BOOK_DATE,'DD')    AS P_DAY,
                      SUM(D.STD_QTY * T1.UNIT_COST) AS TOTAL_COST,
                      TO_CHAR(T1.BOOK_DATE,'YYYY-MM-DD') ppp
                     FROM
                      PROM_PRODUCT P
                     INNER JOIN PROM_PRODUCT_ITEM_DTL D ON P.PRODUCT_ID = D.PRODUCT_ID
                     INNER JOIN T1                      ON D.ITEM_ID = T1.ITEM_ID
                     INNER JOIN IM_ITEM II              ON D.ITEM_ID = II.ITEM_ID
                     GROUP BY
                      P.PRODUCT_ID,
                      T1.BOOK_DATE,
                      P.PRODUCT_NAME
                     ORDER BY
                      T1.BOOK_DATE
                    ) 
                   SELECT
                    ppp,
                    ROUND(NVL(sum(TOTAL_COST),0),2) as TOTAL_COST
                   FROM
                    T2 
                   WHERE
                    ppp > SYSDATE - 30
                    group by ppp
                    order by ppp";

        return $this->db->GetAll($query);
    }
    public function getDataActiveMemberModel() {
        $query = "SELECT
        'Идэвхитэй нийт гишүүд' AS NAME,
        SUM(T.AM) AS CNT
       FROM
        (
         SELECT
          CC.CONTRACT_ID,
          CASE
            WHEN NVL(CC.IS_ACTIVE,0) != 0 THEN COUNT(CC.CUSTOMER_ID)
           END
          AS AM
         FROM
          CON_CONTRACT CC
         WHERE
          NVL(CC.IS_ACTIVE,0) != 0
         AND
          TO_CHAR(CC.CONTRACT_DATE, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')
         AND
          CC.CONTRACT_TYPE_ID != 15231762496164
         GROUP BY
          CC.CONTRACT_ID,
          NVL(CC.IS_ACTIVE,0)
        ) T
       UNION ALL
       SELECT
        'Үндсэн гишүүн' AS NAME,
        NVL(SUM(T.AM),0)  - NVL(SUM(T.PM),0) AS CNT
       FROM
        (
         SELECT
          CC.CONTRACT_ID,
          CASE
            WHEN NVL(CC.IS_ACTIVE,0) != 0 THEN COUNT(CC.CUSTOMER_ID)
           END
          AS AM,
          CASE
            WHEN CC.WFM_STATUS_ID = 1528860702620221 THEN COUNT(CC.CUSTOMER_ID)
           END
          AS PM
         FROM
          CON_CONTRACT CC
         WHERE
          NVL(CC.IS_ACTIVE,0) != 0
         AND
          TO_CHAR(CC.CONTRACT_DATE, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')
         AND
          CC.CONTRACT_TYPE_ID != 15231762496164
         GROUP BY
          CC.CONTRACT_ID,
          CC.WFM_STATUS_ID,
          NVL(CC.IS_ACTIVE,0)
        ) T
       UNION ALL
       SELECT
        'Сунгалт' AS NAME,
        COUNT(CONTRACT_ID) AS PROCENT
       FROM
        CON_CONTRACT_ITEM_BOOK
       WHERE
        BOOK_TYPE_ID = 1127
       AND
        TO_CHAR(BOOK_DATE, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')
       UNION ALL
       SELECT
        'Шинэ' AS NAME,
        NVL(SUM(T.AM),0)  - NVL(SUM(T.PM),0) AS CNT
       FROM
        (
         SELECT
          CC.CONTRACT_ID,
          CASE
            WHEN NVL(CC.IS_ACTIVE,0) != 0 THEN COUNT(CC.CUSTOMER_ID)
           END
          AS AM,
          CASE
            WHEN CC.WFM_STATUS_ID = 1528860702620221 THEN COUNT(CC.CUSTOMER_ID)
           END
          AS PM
         FROM
          CON_CONTRACT CC
         WHERE
          NVL(CC.IS_ACTIVE,0) != 0
         AND
          TO_CHAR(CC.CONTRACT_DATE, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')
         AND
          CC.CONTRACT_TYPE_ID != 15231762496164
         GROUP BY
          CC.CONTRACT_ID,
          CC.WFM_STATUS_ID,
          NVL(CC.IS_ACTIVE,0)
        )T";

        return $this->db->GetAll($query);
    }

    public function getTopFiveItemModel() {
        $query = "SELECT LINE.PRODUCT_ID AS ITEM_ID, ITM.ITEM_NAME, LINE.LINE_TOTAL_AMOUNT, LINE.INVOICE_QTY FROM
                    (SELECT 
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
                    SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_PRICE,
                    SUM(DTL.INVOICE_QTY)  AS INVOICE_QTY
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
                    WHERE (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL) AND II.IS_PRODUCED_ITEM = 1
                    GROUP BY
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1510117117628
                    GROUP BY DTL.PRODUCT_ID
                    ORDER BY SUM(DTL.LINE_TOTAL_PRICE) DESC)LINE
                    INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID WHERE ITM.IS_PRODUCED_ITEM = 1 AND ROWNUM < 6";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveFtItemModel() {
        $query = "SELECT
        LINE.PRODUCT_ID AS ITEM_ID,
        ITM.ITEM_NAME,
        LINE.LINE_TOTAL_AMOUNT,
        LINE.INVOICE_QTY,
        ROWNUM
       FROM
        (
         SELECT
          DTL.PRODUCT_ID,
          SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
          SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
         FROM
          SM_SALES_INVOICE_HEADER HDR
          INNER JOIN SM_SALES_PAYMENT SSP ON HDR.SALES_INVOICE_ID = SSP.SALES_INVOICE_ID
          INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
         WHERE
          TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM') = TO_CHAR(TRUNC(SYSDATE),'YYYY-MM')
          AND   (
           HDR.IS_REMOVED = 0
           OR    HDR.IS_REMOVED IS NULL
          )
          AND   HDR.STORE_ID = 1525063362607
         GROUP BY
          DTL.PRODUCT_ID
         ORDER BY
          SUM(DTL.INVOICE_QTY) DESC
        ) LINE
        INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID
       WHERE
        ROWNUM < 6";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }
    public function getTopFiveFtItemJuiseModel() {
        $query = "SELECT
        LINE.PRODUCT_ID AS ITEM_ID,
        ITM.ITEM_NAME,
        LINE.LINE_TOTAL_AMOUNT,
        LINE.INVOICE_QTY,
        ROWNUM
       FROM
        (
         SELECT
          DTL.PRODUCT_ID,
          SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
          SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
         FROM
          SM_SALES_INVOICE_HEADER HDR
          INNER JOIN SM_SALES_PAYMENT SSP ON HDR.SALES_INVOICE_ID = SSP.SALES_INVOICE_ID
          INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
         WHERE
          TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM') = TO_CHAR(TRUNC(SYSDATE),'YYYY-MM')
          AND   (
           HDR.IS_REMOVED = 0
           OR    HDR.IS_REMOVED IS NULL
          )
          AND   HDR.STORE_ID = 1525063362348
         GROUP BY
          DTL.PRODUCT_ID
         ORDER BY
          SUM(DTL.INVOICE_QTY) DESC
        ) LINE
        INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID
       WHERE
        ROWNUM < 6 ";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }
    
    public function getTopFiveFtItemShangrilaModel() {
        $query = "SELECT
        LINE.PRODUCT_ID AS ITEM_ID,
        ITM.ITEM_NAME,
        LINE.LINE_TOTAL_AMOUNT,
        LINE.INVOICE_QTY,
        ROWNUM
       FROM
        (
         SELECT
          DTL.PRODUCT_ID,
          SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
          SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
         FROM
          SM_SALES_INVOICE_HEADER HDR
          INNER JOIN SM_SALES_PAYMENT SSP ON HDR.SALES_INVOICE_ID = SSP.SALES_INVOICE_ID
          INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
         WHERE
          TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM') = TO_CHAR(TRUNC(SYSDATE - 1),'YYYY-MM')
          AND   (
           HDR.IS_REMOVED = 0
           OR    HDR.IS_REMOVED IS NULL
          )
          AND   HDR.STORE_ID = 1565061062132
         GROUP BY
          DTL.PRODUCT_ID
         ORDER BY
          SUM(DTL.INVOICE_QTY) DESC
        ) LINE
        INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID
       WHERE
        ROWNUM < 6 ";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveRItemModel() {
        $query = "SELECT LINE.PRODUCT_ID AS ITEM_ID, ITM.ITEM_NAME, LINE.LINE_TOTAL_AMOUNT, LINE.INVOICE_QTY FROM
                    (SELECT 
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
                    SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_PRICE,
                    SUM(DTL.INVOICE_QTY)  AS INVOICE_QTY
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
                    WHERE (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL) AND II.IS_PRODUCED_ITEM = 1
                    GROUP BY
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1525063362348
                    GROUP BY DTL.PRODUCT_ID
                    ORDER BY SUM(DTL.LINE_TOTAL_PRICE) DESC)LINE
                    INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID WHERE ITM.IS_PRODUCED_ITEM = 1 AND ROWNUM < 6";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveRItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveR2ItemModel() {
        $query = "SELECT LINE.PRODUCT_ID AS ITEM_ID, ITM.ITEM_NAME, LINE.LINE_TOTAL_AMOUNT, LINE.INVOICE_QTY FROM
                    (SELECT 
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
                    SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_PRICE,
                    SUM(DTL.INVOICE_QTY)  AS INVOICE_QTY
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
                    WHERE (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL) AND II.IS_PRODUCED_ITEM = 1
                    GROUP BY
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1525063362607
                    GROUP BY DTL.PRODUCT_ID
                    ORDER BY SUM(DTL.LINE_TOTAL_PRICE) DESC)LINE
                    INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID WHERE ITM.IS_PRODUCED_ITEM = 1 AND ROWNUM < 6";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveR2ItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveItemStoreModel() {
        $query = "SELECT LINE.PRODUCT_ID AS ITEM_ID, ITM.ITEM_NAME, LINE.LINE_TOTAL_AMOUNT, LINE.INVOICE_QTY FROM
                    (SELECT 
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
                    SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
                    FROM SM_SALES_INVOICE_HEADER HDR
                    INNER JOIN
                    (SELECT
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID,
                    SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_PRICE,
                    SUM(DTL.INVOICE_QTY)  AS INVOICE_QTY
                    FROM
                    SM_SALES_INVOICE_DETAIL DTL
                    INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
                    WHERE (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL) AND II.IS_PRODUCED_ITEM = 1
                    GROUP BY
                    DTL.SALES_INVOICE_ID,
                    DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
                    WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                    AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND HDR.STORE_ID =1510117118100
                    GROUP BY DTL.PRODUCT_ID
                    ORDER BY SUM(DTL.LINE_TOTAL_PRICE) DESC)LINE
                    INNER JOIN IM_ITEM ITM ON LINE.PRODUCT_ID = ITM.ITEM_ID WHERE ITM.IS_PRODUCED_ITEM = 1 AND ROWNUM < 6";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveItemStoreDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveSupplyItem() {
        $query = "SELECT 
                ITEM_ID,
                QTY AS QUANTITY,
                ITEM_NAME
                FROM (
                 SELECT II.ITEM_ID, SUM(BD.FACT_2) QTY,
                 II.ITEM_NAME
                 FROM IC_INVOICE_BOOK B
                  INNER JOIN IC_INVOICE_BOOK_DTL BD ON BD.INVOICE_BOOK_ID = B.ID
                  LEFT JOIN IM_ITEM II              ON BD.DIM_1 = II.ITEM_ID
                  WHERE
                   BD.WFM_STATUS_ID != 1512120609071303 AND
                   II.ITEM_NAME IS NOT NULL
                  GROUP BY
                   II.ITEM_ID,
                   II.ITEM_NAME
                   ORDER BY QTY DESC
                )
                WHERE ROWNUM <= 5";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveSupplyItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveIndustryItem() {
        $query = "SELECT
                    T1.ITEM_NAME,
                    T1.ITEM_ID,
                    T1.QUANTITY
                 FROM
                    (
                        SELECT
                            II.ITEM_NAME,
                            SUM(NVL(P.QUANTITY,0) ) QUANTITY,
                            II.ITEM_ID
                        FROM
                            TM_TASK TT
                            INNER JOIN IM_ITEM II ON TT.ITEM_ID = II.ITEM_ID
                            INNER JOIN TM_TASK_BOOK B ON TT.TASK_BOOK_ID = B.ID
                            INNER JOIN (
                                SELECT
                                    PR.PRODUCT_ID,
                                    WL.LOCATION_TYPE_ID
                                FROM
                                    SM_SECTION_PRODUCT PR
                                    INNER JOIN SM_SECTION SCT ON PR.SECTION_ID = SCT.SECTION_ID
                                    INNER JOIN WH_LOCATION WL ON SCT.LOCATION_ID = WL.LOCATION_ID
                                WHERE
                                    SCT.CODE = '01-03'
                                GROUP BY
                                    PR.PRODUCT_ID,
                                    WL.LOCATION_TYPE_ID
                            ) PR ON TT.ITEM_ID = PR.PRODUCT_ID
                            LEFT JOIN (
                                SELECT
                                    TASK_ID,
                                    SUM(QUANTITY) QUANTITY
                                FROM
                                    TM_TASK_PERFORMANCE
                                GROUP BY
                                    TASK_ID
                            ) P ON TT.TASK_ID = P.TASK_ID
                        WHERE
                            TRUNC(B.ORDER_DATE) = TRUNC(SYSDATE)
                        GROUP BY
                            II.ITEM_NAME,
                            II.ITEM_ID
                       ORDER BY QUANTITY DESC
                    ) T1
                 WHERE ROWNUM <= 5";
        
        $result = $this->db->GetAll($query);
        
        $resArr = array();
        foreach($result as $row) {
            $dtlItem = self::getTopFiveIndustryItemDtlModel($row['ITEM_ID']);
            $row['itemDtl'] = $dtlItem;
            array_push($resArr, $row);
        }

        return $resArr;
    }

    public function getTopFiveItemDtlModel($itemId) {
        $query = "SELECT
                II.ITEM_ID,
               TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
               SUM(DTL.LINE_TOTAL_AMOUNT) AS LINE_TOTAL_AMOUNT,
               SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_HEADER HDR
              INNER JOIN
              (SELECT
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID,
              SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
              SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_DETAIL DTL
              WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL AND DTL.PRODUCT_ID = $itemId
              GROUP BY
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
              INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
              WHERE TRUNC(HDR.INVOICE_DATE) BETWEEN TRUNC(SYSDATE - 30) AND TRUNC(SYSDATE + 1)
              AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND II.IS_PRODUCED_ITEM = 1 AND HDR.STORE_ID =1510117117628
              GROUP BY
              II.ITEM_ID,
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')
              ORDER BY
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')";

        return $this->db->GetAll($query);
    }

    public function getTopFiveRItemDtlModel($itemId) {
        $query = "SELECT
                II.ITEM_ID,
               TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
               SUM(DTL.LINE_TOTAL_AMOUNT) AS LINE_TOTAL_AMOUNT,
               SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_HEADER HDR
              INNER JOIN
              (SELECT
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID,
              SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
              SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_DETAIL DTL
              WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL AND DTL.PRODUCT_ID = $itemId
              GROUP BY
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
              INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
              WHERE TRUNC(HDR.INVOICE_DATE) BETWEEN TRUNC(SYSDATE - 30) AND TRUNC(SYSDATE + 1)
              AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND II.IS_PRODUCED_ITEM = 1 AND HDR.STORE_ID =1525063362348
              GROUP BY
              II.ITEM_ID,
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')
              ORDER BY
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')";

        return $this->db->GetAll($query);
    }

    public function getTopFiveR2ItemDtlModel($itemId) {
        $query = "SELECT
                II.ITEM_ID,
               TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
               SUM(DTL.LINE_TOTAL_AMOUNT) AS LINE_TOTAL_AMOUNT,
               SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_HEADER HDR
              INNER JOIN
              (SELECT
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID,
              SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
              SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_DETAIL DTL
              WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL AND DTL.PRODUCT_ID = $itemId
              GROUP BY
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
              INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
              WHERE TRUNC(HDR.INVOICE_DATE) BETWEEN TRUNC(SYSDATE - 30) AND TRUNC(SYSDATE + 1)
              AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND II.IS_PRODUCED_ITEM = 1 AND HDR.STORE_ID =1525063362607
              GROUP BY
              II.ITEM_ID,
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')
              ORDER BY
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')";

        return $this->db->GetAll($query);
    }

    public function getTopFiveItemStoreDtlModel($itemId) {
        $query = "SELECT
                II.ITEM_ID,
               TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD') AS INVOICE_DATE,
               SUM(DTL.LINE_TOTAL_AMOUNT) AS LINE_TOTAL_AMOUNT,
               SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_HEADER HDR
              INNER JOIN
              (SELECT
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID,
              SUM(DTL.LINE_TOTAL_PRICE) AS LINE_TOTAL_AMOUNT,
              SUM(DTL.INVOICE_QTY) AS INVOICE_QTY
              FROM
              SM_SALES_INVOICE_DETAIL DTL
              WHERE DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL AND DTL.PRODUCT_ID = $itemId
              GROUP BY
              DTL.SALES_INVOICE_ID,
              DTL.PRODUCT_ID) DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID
              INNER JOIN IM_ITEM II ON II.ITEM_ID = DTL.PRODUCT_ID
              WHERE TRUNC(HDR.INVOICE_DATE) BETWEEN TRUNC(SYSDATE - 30) AND TRUNC(SYSDATE + 1)
              AND (HDR.is_removed = 0 OR HDR.is_removed IS NULL) AND II.IS_PRODUCED_ITEM = 1 AND HDR.STORE_ID = 1510117118100
              GROUP BY
              II.ITEM_ID,
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')
              ORDER BY
              TO_CHAR(HDR.INVOICE_DATE, 'YYYY-MM-DD')";

        return $this->db->GetAll($query);
    }

    public function getTopFiveSupplyItemDtlModel($itemId) {
        $query = "SELECT
                    II.ITEM_ID,
                    II.ITEM_NAME,
                    SUM(BD.FACT_2) QUANTITY,
                    TRUNC(B.BOOK_DATE) ORDER_DATE
                   FROM
                    IC_INVOICE_BOOK B
                   INNER JOIN IC_INVOICE_BOOK_DTL BD ON BD.INVOICE_BOOK_ID = B.ID
                   LEFT JOIN IM_ITEM II ON BD.DIM_1 = II.ITEM_ID
                   WHERE
                    B.BOOK_TYPE_ID = 40000008 AND
                    B.BOOK_DATE > SYSDATE - 30 AND
                    BD.WFM_STATUS_ID != 1512120609071303 AND
                    II.ITEM_ID = $itemId
                   GROUP BY
                    TRUNC(B.BOOK_DATE),
                    II.ITEM_NAME,
                    II.ITEM_ID
                   ORDER BY
                    TRUNC(B.BOOK_DATE)";

        return $this->db->GetAll($query);
    }

    public function getTopFiveIndustryItemDtlModel($itemId) {
        $query = "SELECT T2.ITEM_NAME,
                    T2.QUANTITY AS QUANTITY,
                    T2.ORDER_DATE
                  FROM
                    (SELECT II.ITEM_NAME,
                      SUM(NVL(P.QUANTITY,0) ) QUANTITY,
                      TRUNC(B.ORDER_DATE) ORDER_DATE,
                      II.ITEM_ID
                    FROM TM_TASK TT
                    INNER JOIN IM_ITEM II
                    ON TT.ITEM_ID = II.ITEM_ID
                    INNER JOIN TM_TASK_BOOK B
                    ON TT.TASK_BOOK_ID = B.ID
                    INNER JOIN
                      (SELECT PR.PRODUCT_ID,
                        WL.LOCATION_TYPE_ID
                      FROM SM_SECTION_PRODUCT PR
                      INNER JOIN SM_SECTION SCT
                      ON PR.SECTION_ID = SCT.SECTION_ID
                      INNER JOIN WH_LOCATION WL
                      ON SCT.LOCATION_ID = WL.LOCATION_ID
                      WHERE SCT.CODE     = '01-03'
                      GROUP BY PR.PRODUCT_ID,
                        WL.LOCATION_TYPE_ID
                      ) PR ON TT.ITEM_ID = PR.PRODUCT_ID
                    LEFT JOIN
                      (SELECT TASK_ID,
                        SUM(QUANTITY) QUANTITY
                      FROM TM_TASK_PERFORMANCE
                      GROUP BY TASK_ID
                      ) P
                    ON TT.TASK_ID = P.TASK_ID
                    WHERE TRUNC(B.ORDER_DATE) > TRUNC(SYSDATE) - 30
                    AND II.ITEM_ID = $itemId
                    AND P.QUANTITY != 0
                    GROUP BY II.ITEM_NAME,
                      TRUNC(B.ORDER_DATE),
                      II.ITEM_ID
                    ) T2
                    ORDER BY 
                        T2.ORDER_DATE";

        return $this->db->GetAll($query);
    }

    public function getDataSalesDayYearChartModel() {
        $query = "SELECT 
                    'begin_year' AS TIME_RANGE,
                    SUM(TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER
                    WHERE INVOICE_DATE BETWEEN TO_DATE(TO_CHAR(SYSDATE, 'YYYY') || '-01-01', 'YYYY-MM-DD') AND SYSDATE
                    union all
                    SELECT 
                    'last_month' AS TIME_RANGE,
                    SUM(TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER
                    WHERE TO_CHAR(invoice_date, 'YYYYMM') = TO_CHAR(add_months(SYSDATE,-1), 'YYYYMM') AND (is_removed = 0 OR is_removed IS NULL)
                    union all
                    SELECT 
                    'this_month' AS TIME_RANGE,
                    SUM(TOTAL) AS TOTAL_AMOUNT
                    FROM SM_SALES_INVOICE_HEADER
                    WHERE TO_CHAR(invoice_date, 'YYYYMM') = TO_CHAR(SYSDATE, 'YYYYMM') AND (is_removed = 0 OR is_removed IS NULL)";

        return $this->db->GetAll($query);
    }

    public function getDataTop5SupplierModel() {
        $query = "SELECT
                    CUSTOMER_NAME,
                    UNIT_AMOUNT
                   FROM
                    (
                     SELECT
                      C.CUSTOMER_NAME,
                      SUM(BD.FACT_4) UNIT_AMOUNT
                     FROM
                      IC_INVOICE_BOOK B
                     INNER JOIN IC_INVOICE_BOOK_DTL BD ON BD.INVOICE_BOOK_ID = B.ID
                     INNER JOIN CRM_CUSTOMER C         ON C.CUSTOMER_ID = B.DIM_1
                     LEFT JOIN IM_ITEM II              ON BD.DIM_1 = II.ITEM_ID
                     WHERE
                      B.BOOK_TYPE_ID = 40000008 AND
                      CUSTOMER_NAME IS NOT NULL AND
                      BD.WFM_STATUS_ID = 1506563637022636
                     GROUP BY
                      C.CUSTOMER_NAME
                     ORDER BY
                      UNIT_AMOUNT DESC
                    )
                    IC
                   WHERE
                    ROWNUM <= 5";

        return $this->db->GetAll($query);
    }

    public function getDataItemReturnSupplyModel() {
        $query = "SELECT
                    II.ITEM_NAME,
                    SUM(BD.FACT_1) RETURN_QTY,
                    TO_CHAR(TRUNC(B.BOOK_DATE), 'YYYY-MM-DD') BOOK_DATE
                    FROM
                    IC_INVOICE_BOOK B
                    INNER JOIN IC_INVOICE_BOOK_DTL BD ON BD.INVOICE_BOOK_ID = B.ID
                    LEFT JOIN CRM_CUSTOMER C ON C.CUSTOMER_ID = B.DIM_1
                    LEFT JOIN IM_ITEM II ON BD.DIM_1 = II.ITEM_ID
                    WHERE
                    B.BOOK_TYPE_ID = 40000008 
                    AND 
                    BD.WFM_STATUS_ID = 1512120609071303
                    AND
                    II.ITEM_NAME IS NOT NULL
                    GROUP BY II.ITEM_NAME,
                    TRUNC(B.BOOK_DATE)
                    ORDER BY TRUNC(B.BOOK_DATE)";

        return $this->db->GetAll($query);
    }

    public function getDataItemReturnIndustryModel() {
        $query = "SELECT 
                    TO_CHAR(TRUNC(R.CREATED_DATE), 'YYYY-MM-DD') AS CREATED_DATE,
                    SUM(NVL(R.RETURN_QTY,0)) AS RETURN_QTY,
                    CASE WHEN T.NAME IS NULL
                    THEN 'Шалтгаангүй'
                    ELSE T.NAME
                    END AS NAME
                    FROM tm_task_return r
                    left join TASK_RETURN_TYPE t on r.return_type_id = t.id
                    GROUP BY TRUNC(R.CREATED_DATE), T.NAME
                    ORDER BY TRUNC(R.CREATED_DATE)";

        return $this->db->GetAll($query);
    }

    public function getDataItemReturnPerIndustryModel() {
        $query = "SELECT
                    II.ITEM_NAME,
                    (NVL(SUM(RT.RETURN_QTY),0) / NVL(SUM(TT.QUANTITY),0)) * 100 AS RETURN_PERCENT
                   FROM
                    TM_TASK TT
                   INNER JOIN IM_ITEM II ON TT.ITEM_ID = II.ITEM_ID
                   INNER JOIN TM_TASK_BOOK B ON TT.TASK_BOOK_ID = B.ID
                   INNER JOIN (
                   SELECT TASK_ID, SUM(NVL(RETURN_QTY,0)) RETURN_QTY FROM TM_TASK_RETURN WHERE NVL(IS_AFTER_PROCESSING,0) = 1 GROUP BY TASK_ID
                   )RT ON TT.TASK_ID = RT.TASK_ID
                   INNER JOIN
                    ( SELECT PR.PRODUCT_ID, WL.LOCATION_TYPE_ID FROM SM_SECTION_PRODUCT PR
                     INNER JOIN SM_SECTION SCT ON PR.SECTION_ID = SCT.SECTION_ID
                     INNER JOIN WH_LOCATION WL ON SCT.LOCATION_ID = WL.LOCATION_ID
                     WHERE
                      SCT.CODE = '01-03'
                     GROUP BY
                      PR.PRODUCT_ID,
                      WL.LOCATION_TYPE_ID
                    )
                    PR ON TT.ITEM_ID = PR.PRODUCT_ID 
                   WHERE TRUNC(B.ORDER_DATE) = TRUNC(SYSDATE)
                   GROUP BY II.ITEM_NAME";

        return $this->db->GetAll($query);
    }
    
    public function sendEmailModel() {
        $param = Input::postData();
        $param = array(
            'subjectTxt' => 'Test Subject',
            'messageTxt' => 'Test Message',
            'mailList' => array(
                'ulaankhuu@veritech.mn'
            ),
            'urlList' => array(
                'https://dev.veritech.mn/dashboard/sales'
            ),
        );
        $data  = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'send_mail', $param);
        var_dump($data); die;

        if ($data['status'] === 'success')
                $response = array('text'   => 'Амжилттай хадгалагдлаа.', 'status' => 'success', 'title'  => 'Success',
                'result' => $data['result']['save_amount']);
        else
                $response = array('text' => $data['text'], 'status' => 'error', 'title' => 'Error');

        return $response;
    }    

    public function getAllCountSalesModel() {
        $query = "SELECT
                    TO_CHAR(
                        hdr.total,
                        '999,999,999'
                    ) AS total
                FROM
                    (
                        SELECT
                            SUM(hdr.total) AS total
                        FROM
                            sm_sales_invoice_header hdr
                        WHERE
                            (
                                    hdr.is_removed = 0
                                OR
                                    hdr.is_removed IS NULL
                            )
                    ) hdr";

        return $this->db->GetOne($query);
    }    
    
    public function getCashBeginAmountModel() {
        $query = "SELECT 
                    SUM(C1.BEGIN_AMOUNT) AS BEGIN_AMOUNT, 
                    SUM(GL.DEBIT_AMOUNT) AS DEBIT_AMOUNT, 
                    SUM(GL.CREDIT_AMOUNT) AS CREDIT_AMOUNT, 
                    SUM(C2.END_AMOUNT) AS END_AMOUNT
                   FROM 
                    CM_CASHIER_KEEPER CK 
                    JOIN FIN_ACCOUNT FA ON CK.ACCOUNT_ID = FA.ACCOUNT_ID 
                    LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = FA.CURRENCY_ID 
                    LEFT JOIN (
                      SELECT 
                        CB.CASHIER_KEEPER_ID, 
                        SUM(CB.DEBIT_AMOUNT - CB.CREDIT_AMOUNT ) AS BEGIN_AMOUNT, 
                        SUM( CB.DEBIT_AMOUNT_BASE - CB.CREDIT_AMOUNT_BASE) AS BEGIN_AMOUNT_BASE 
                      FROM 
                        CM_INV_CASH CB 
                      WHERE 
                        TRUNC(CB.BOOK_DATE) < TRUNC(sysdate-1) 
                      GROUP BY 
                        CB.CASHIER_KEEPER_ID
                    ) C1 ON CK.ID = C1.CASHIER_KEEPER_ID 
                    LEFT JOIN (
                      SELECT 
                        CG.CASHIER_KEEPER_ID, 
                        SUM(CG.DEBIT_AMOUNT) AS DEBIT_AMOUNT, 
                        SUM(CG.DEBIT_AMOUNT_BASE) AS DEBIT_AMOUNT_BASE, 
                        SUM(CG.CREDIT_AMOUNT) AS CREDIT_AMOUNT, 
                        SUM(CG.CREDIT_AMOUNT_BASE) AS CREDIT_AMOUNT_BASE 
                      FROM 
                        CM_INV_CASH CG 
                      WHERE 
                        TRUNC(CG.BOOK_DATE) BETWEEN TRUNC(sysdate-1) 
                        AND TRUNC(sysdate-1)                            
                      GROUP BY 
                        CG.CASHIER_KEEPER_ID
                    ) GL ON CK.ID = GL.CASHIER_KEEPER_ID 
                    LEFT JOIN (
                      SELECT 
                        CE.CASHIER_KEEPER_ID, 
                        SUM(CE.DEBIT_AMOUNT - CE.CREDIT_AMOUNT) AS END_AMOUNT, 
                        SUM(CE.DEBIT_AMOUNT_BASE - CE.CREDIT_AMOUNT_BASE) AS END_AMOUNT_BASE 
                      FROM 
                        CM_INV_CASH CE 
                      WHERE 
                        TRUNC(CE.BOOK_DATE) <= TRUNC(sysdate-1)
                      GROUP BY 
                        CE.CASHIER_KEEPER_ID
                    ) C2 ON CK.ID = C2.CASHIER_KEEPER_ID";
        
        return $this->db->GetRow($query);
    }    
    
    public function getCashEndAmountModel() {
        $query = "SELECT 
                SUM(CASH.DEBIT_AMOUNT - CASH.CREDIT_AMOUNT) AS END_AMOUNT,
                SUM(CASE WHEN ACC.CURRENCY_ID = 11337947158805
                      THEN 0
                      ELSE CASH.DEBIT_AMOUNT_BASE - CASH.CREDIT_AMOUNT_BASE
                     END )AS BEGIN_AMOUNT_BASE
                FROM 
                CM_CASHIER_KEEPER CK
                INNER JOIN CM_INV_CASH CASH ON CK.ID = CASH.CASHIER_KEEPER_ID
                INNER JOIN FIN_ACCOUNT ACC ON CK .ACCOUNT_ID = ACC.ACCOUNT_ID
                LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = ACC.CURRENCY_ID
                WHERE TRUNC(CASH.BOOK_DATE) < TRUNC(SYSDATE-1)";
        
        return $this->db->GetRow($query);
    }    
    
    public function getAllCashCurrencyModel() {
        $query = "
            SELECT 
            CK.CASH_NYRAV_NAME || ' ' ||
            CURR.CURRENCY_NAME  AS BANK,
              CURR.CURRENCY_CODE,
             C1.BEGIN_AMOUNT, 
             CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN C1.BEGIN_AMOUNT_BASE ELSE 0 END AS BEGIN_AMOUNT_BASE, 
             GL.DEBIT_AMOUNT, 
             CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN GL.DEBIT_AMOUNT_BASE ELSE 0 END AS DEBIT_AMOUNT_BASE, 
             CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN GL.CREDIT_AMOUNT_BASE ELSE 0 END AS CREDIT_AMOUNT_BASE, 
             GL.CREDIT_AMOUNT, 
             C2.END_AMOUNT, 
             CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN C2.END_AMOUNT_BASE ELSE 0 END AS END_AMOUNT_BASE
            FROM 
             CM_CASHIER_KEEPER CK 
             JOIN FIN_ACCOUNT FA ON CK.ACCOUNT_ID = FA.ACCOUNT_ID 
             LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = FA.CURRENCY_ID 
             LEFT JOIN (
               SELECT 
                 CB.CASHIER_KEEPER_ID, 
                 SUM(CB.DEBIT_AMOUNT - CB.CREDIT_AMOUNT ) AS BEGIN_AMOUNT, 
                 SUM( CB.DEBIT_AMOUNT_BASE - CB.CREDIT_AMOUNT_BASE) AS BEGIN_AMOUNT_BASE 
               FROM 
                 CM_INV_CASH CB 
               WHERE 
                 TRUNC(CB.BOOK_DATE) < TRUNC(sysdate-1) 
               GROUP BY 
                 CB.CASHIER_KEEPER_ID
             ) C1 ON CK.ID = C1.CASHIER_KEEPER_ID 
             LEFT JOIN (
               SELECT 
                 CG.CASHIER_KEEPER_ID, 
                 SUM(CG.DEBIT_AMOUNT) AS DEBIT_AMOUNT, 
                 SUM(CG.DEBIT_AMOUNT_BASE) AS DEBIT_AMOUNT_BASE, 
                 SUM(CG.CREDIT_AMOUNT) AS CREDIT_AMOUNT, 
                 SUM(CG.CREDIT_AMOUNT_BASE) AS CREDIT_AMOUNT_BASE 
               FROM 
                 CM_INV_CASH CG 
               WHERE 
                TRUNC(CG.BOOK_DATE) BETWEEN TRUNC(sysdate-1) 
                AND TRUNC(sysdate-1)                  
               GROUP BY 
                 CG.CASHIER_KEEPER_ID
             ) GL ON CK.ID = GL.CASHIER_KEEPER_ID 
             LEFT JOIN (
               SELECT 
                 CE.CASHIER_KEEPER_ID, 
                 SUM(CE.DEBIT_AMOUNT - CE.CREDIT_AMOUNT) AS END_AMOUNT, 
                 SUM(CE.DEBIT_AMOUNT_BASE - CE.CREDIT_AMOUNT_BASE) AS END_AMOUNT_BASE 
               FROM 
                 CM_INV_CASH CE 
               WHERE 
                 TRUNC(CE.BOOK_DATE) <= TRUNC(sysdate-1)
               GROUP BY 
                 CE.CASHIER_KEEPER_ID
             ) C2 ON CK.ID = C2.CASHIER_KEEPER_ID";
        
        return $this->db->GetAll($query);
    } 
    
    public function getDataCashIncomeModel() {
        $query = "SELECT 
            CK.CASH_NYRAV_NAME,
            CF.NAME AS SUB_CATEGORY_NAME,
            SUM(BOOK.DEBIT_AMOUNT ) AS DEBIT_AMOUNT,
            SUM(CASE WHEN ACC.CURRENCY_ID = 11337947158805
                  THEN 0
                  ELSE BOOK.DEBIT_AMOUNT_BASE
                 END ) AS DEBIT_AMOUNT_BASE
            FROM 
            CM_CASHIER_KEEPER CK
            INNER JOIN CM_INV_CASH BOOK ON CK.ID = BOOK.CASHIER_KEEPER_ID
            INNER JOIN FIN_ACCOUNT ACC         ON CK .ACCOUNT_ID = ACC.ACCOUNT_ID
            LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = ACC.CURRENCY_ID
            LEFT JOIN FIN_CASH_FLOW_SUB_CATEGORY CF ON BOOK.CASH_FLOW_SUB_CATEGORY_ID = CF.CASH_FLOW_SUB_CATEGORY_ID
            LEFT JOIN
             ( SELECT GLM.INVOICE_ID, GLM.GENERAL_LEDGER_ID,
             LISTAGG( CASE WHEN GLO.CASH_FLOW_SUB_CATEGORY_CODE IS NOT NULL THEN ( GLO.CASH_FLOW_SUB_CATEGORY_NAME) ELSE NULL END,',') WITHIN GROUP ( ORDER BY GLO.CASH_FLOW_SUB_CATEGORY_ID) AS SUB_CATEGORY_NAME
             FROM FIN_GENERAL_LEDGER_MAP GLM
              INNER JOIN FIN_GENERAL_LEDGER_OPP GLO ON GLM.GENERAL_LEDGER_ID = GLO.GENERAL_LEDGER_ID
              WHERE
               GLO.BOOK_TYPE_ID NOT IN (1, 17) AND
               GLM.OBJECT_ID = 20003
              GROUP BY
               GLM.INVOICE_ID,
               GLM.GENERAL_LEDGER_ID
             )
             GLM ON ( GLM.INVOICE_ID = BOOK.ID )
            WHERE  TRUNC(BOOK.BOOK_DATE) = TRUNC(sysdate-1) AND BOOK.IS_DEBIT=1
            GROUP BY  CK.CASH_NYRAV_NAME,
            CF.NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataCashOutcomeModel() {
        $query = "SELECT 
            CK.CASH_NYRAV_NAME,
           CF.NAME AS SUB_CATEGORY_NAME,
           SUM(BOOK.CREDIT_AMOUNT ) AS CREDIT_AMOUNT,
           SUM(CASE WHEN ACC.CURRENCY_ID = 11337947158805
                 THEN 0
                 ELSE BOOK.CREDIT_AMOUNT_BASE
                END ) AS CREDIT_AMOUNT_BASE
           FROM 
           CM_CASHIER_KEEPER CK
           INNER JOIN CM_INV_CASH BOOK ON CK.ID = BOOK.CASHIER_KEEPER_ID
           INNER JOIN FIN_ACCOUNT ACC         ON CK .ACCOUNT_ID = ACC.ACCOUNT_ID
           LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = ACC.CURRENCY_ID
           LEFT JOIN FIN_CASH_FLOW_SUB_CATEGORY CF ON BOOK.CASH_FLOW_SUB_CATEGORY_ID = CF.CASH_FLOW_SUB_CATEGORY_ID
           LEFT JOIN
            ( SELECT GLM.INVOICE_ID, GLM.GENERAL_LEDGER_ID,
            LISTAGG( CASE WHEN GLO.CASH_FLOW_SUB_CATEGORY_CODE IS NOT NULL THEN ( GLO.CASH_FLOW_SUB_CATEGORY_NAME) ELSE NULL END,',') WITHIN GROUP ( ORDER BY GLO.CASH_FLOW_SUB_CATEGORY_ID) AS SUB_CATEGORY_NAME
            FROM FIN_GENERAL_LEDGER_MAP GLM
             INNER JOIN FIN_GENERAL_LEDGER_OPP GLO ON GLM.GENERAL_LEDGER_ID = GLO.GENERAL_LEDGER_ID
             WHERE
              GLO.BOOK_TYPE_ID NOT IN (1, 17) AND
              GLM.OBJECT_ID = 20003
             GROUP BY
              GLM.INVOICE_ID,
              GLM.GENERAL_LEDGER_ID
            )
            GLM ON ( GLM.INVOICE_ID = BOOK.ID ) WHERE
            TRUNC(BOOK.BOOK_DATE) = TRUNC(sysdate-1) AND BOOK.IS_DEBIT=0
           GROUP BY  CK.CASH_NYRAV_NAME,
           CF.NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataCashByActivityModel() {
        $query = "SELECT
                TO_CHAR(CASH.BOOK_DATE, 'DD') AS REG_DATE,
                CASH.BOOK_DATE,
                COALESCE(SUM(CASH.DEBIT_AMOUNT), 0)  AS DEBIT_AMOUNT,
                COALESCE(SUM(CASH.CREDIT_AMOUNT), 0) AS CREDIT_AMOUNT
                FROM
                  CM_INV_CASH CASH 
               INNER JOIN
                ( SELECT START_DATE, END_DATE, PERIOD_NAME, ID FROM FIN_FISCAL_PERIOD WHERE TYPE_ID = 1 AND TO_CHAR(START_DATE, 'yyyy') = TO_CHAR(SYSDATE, 'yyyy')
                ) LINE ON CASH.BOOK_DATE BETWEEN LINE.START_DATE AND LINE.END_DATE
               WHERE
                TRUNC(SYSDATE) BETWEEN LINE.START_DATE AND LINE.END_DATE
               GROUP BY
                CASH.BOOK_DATE
               ORDER BY
                CASH.BOOK_DATE";

        return $this->db->GetAll($query);
    }    
    
    public function getBankBeginAmountModel() {
        $query = "SELECT 
                SUM(C1.BEGIN_AMOUNT) AS BEGIN_AMOUNT, 
                SUM(GL.DEBIT_AMOUNT) AS DEBIT_AMOUNT, 
                SUM(GL.CREDIT_AMOUNT) AS CREDIT_AMOUNT, 
                SUM(C2.END_AMOUNT) AS END_AMOUNT
               FROM 
                CM_BANK_ACCOUNT CB 
                JOIN FIN_ACCOUNT FA ON CB.ACCOUNT_ID = FA.ACCOUNT_ID 
                LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = FA.CURRENCY_ID 
                LEFT JOIN (
                  SELECT 
                    CB.BANK_ACCOUNT_ID, 
                    SUM(
                      CB.DEBIT_AMOUNT - CB.CREDIT_AMOUNT
                    ) AS BEGIN_AMOUNT, 
                    SUM(
                      CB.DEBIT_AMOUNT_BASE - CB.CREDIT_AMOUNT_BASE
                    ) AS BEGIN_AMOUNT_BASE 
                  FROM 
                    CM_INV_BANK CB 
                  WHERE 
                     TRUNC(CB.BOOK_DATE) < TRUNC(sysdate-1)
                  GROUP BY 
                    CB.BANK_ACCOUNT_ID
                ) C1 ON CB.ID = C1.BANK_ACCOUNT_ID 
                LEFT JOIN (
                  SELECT 
                    CB.BANK_ACCOUNT_ID, 
                    SUM(CB.DEBIT_AMOUNT) AS DEBIT_AMOUNT, 
                    SUM(CB.DEBIT_AMOUNT_BASE) AS DEBIT_AMOUNT_BASE, 
                    SUM(CB.CREDIT_AMOUNT) AS CREDIT_AMOUNT, 
                    SUM(CB.CREDIT_AMOUNT_BASE) AS CREDIT_AMOUNT_BASE 
                  FROM 
                    CM_INV_BANK CB 
                  WHERE 
                    TRUNC(CB.BOOK_DATE) BETWEEN TRUNC(sysdate-1)   AND TRUNC(sysdate-1)
                  GROUP BY 
                    CB.BANK_ACCOUNT_ID
                ) GL ON CB.ID = GL.BANK_ACCOUNT_ID 
                LEFT JOIN (
                  SELECT 
                    CB.BANK_ACCOUNT_ID, 
                    SUM(
                      CB.DEBIT_AMOUNT - CB.CREDIT_AMOUNT
                    ) AS END_AMOUNT, 
                    SUM(
                      CB.DEBIT_AMOUNT_BASE - CB.CREDIT_AMOUNT_BASE
                    ) AS END_AMOUNT_BASE 
                  FROM 
                    CM_INV_BANK CB  
                  WHERE TRUNC(CB.BOOK_DATE) <= TRUNC(sysdate-1)
                  GROUP BY 
                    CB.BANK_ACCOUNT_ID
                ) C2 ON CB.ID = C2.BANK_ACCOUNT_ID";
        
        return $this->db->GetRow($query);
    }    
    
    public function getAllBankCurrencyModel() {
        
        (Array) $param = array(
            'systemMetaGroupId' => '1674185487421512',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = Arr::changeKeyUpper($data['result']);
        }
        
        return $resultArr;
        
//        $query = "SELECT 
//                CB.BANK_ACCOUNT_DESC_L AS BANK, 
//                CURR.CURRENCY_CODE,
//                C1.BEGIN_AMOUNT, 
//                CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN C1.BEGIN_AMOUNT_BASE ELSE 0 END AS BEGIN_AMOUNT_BASE, 
//                GL.DEBIT_AMOUNT, 
//                CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN GL.DEBIT_AMOUNT_BASE ELSE 0 END AS DEBIT_AMOUNT_BASE, 
//                CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN GL.CREDIT_AMOUNT_BASE ELSE 0 END AS CREDIT_AMOUNT_BASE, 
//                GL.CREDIT_AMOUNT, 
//                C2.END_AMOUNT, 
//                CASE WHEN CURR.CURRENCY_CODE <> 'MNT' THEN C2.END_AMOUNT_BASE ELSE 0 END AS END_AMOUNT_BASE
//               FROM 
//                CM_BANK_ACCOUNT CB 
//                JOIN FIN_ACCOUNT FA ON CB.ACCOUNT_ID = FA.ACCOUNT_ID 
//                LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = FA.CURRENCY_ID 
//                LEFT JOIN (
//                  SELECT 
//                    CB.BANK_ACCOUNT_ID, 
//                    SUM(
//                      CB.DEBIT_AMOUNT - CB.CREDIT_AMOUNT
//                    ) AS BEGIN_AMOUNT, 
//                    SUM(
//                      CB.DEBIT_AMOUNT_BASE - CB.CREDIT_AMOUNT_BASE
//                    ) AS BEGIN_AMOUNT_BASE 
//                  FROM 
//                    CM_INV_BANK CB 
//                  WHERE 
//                     TRUNC(CB.BOOK_DATE) < TRUNC(sysdate-1)
//                  GROUP BY 
//                    CB.BANK_ACCOUNT_ID
//                ) C1 ON CB.ID = C1.BANK_ACCOUNT_ID 
//                LEFT JOIN (
//                  SELECT 
//                    CB.BANK_ACCOUNT_ID, 
//                    SUM(CB.DEBIT_AMOUNT) AS DEBIT_AMOUNT, 
//                    SUM(CB.DEBIT_AMOUNT_BASE) AS DEBIT_AMOUNT_BASE, 
//                    SUM(CB.CREDIT_AMOUNT) AS CREDIT_AMOUNT, 
//                    SUM(CB.CREDIT_AMOUNT_BASE) AS CREDIT_AMOUNT_BASE 
//                  FROM 
//                    CM_INV_BANK CB 
//                  WHERE 
//                    TRUNC(CB.BOOK_DATE) BETWEEN TRUNC(sysdate-1)   AND TRUNC(sysdate-1)
//                  GROUP BY 
//                    CB.BANK_ACCOUNT_ID
//                ) GL ON CB.ID = GL.BANK_ACCOUNT_ID 
//                LEFT JOIN (
//                  SELECT 
//                    CB.BANK_ACCOUNT_ID, 
//                    SUM(
//                      CB.DEBIT_AMOUNT - CB.CREDIT_AMOUNT
//                    ) AS END_AMOUNT, 
//                    SUM(
//                      CB.DEBIT_AMOUNT_BASE - CB.CREDIT_AMOUNT_BASE
//                    ) AS END_AMOUNT_BASE 
//                  FROM 
//                    CM_INV_BANK CB  
//                  WHERE TRUNC(CB.BOOK_DATE) <= TRUNC(sysdate-1)
//                  GROUP BY 
//                    CB.BANK_ACCOUNT_ID
//                ) C2 ON CB.ID = C2.BANK_ACCOUNT_ID";
//        
//        return $this->db->GetAll($query);
    } 
    
    public function getDataBankIncomeModel() {
        $query = "SELECT 
                CB.BANK_ACCOUNT_DESC_L,
                CF.NAME AS SUB_CATEGORY_NAME,
                SUM(BOOK.DEBIT_AMOUNT ) AS DEBIT_AMOUNT,
                SUM(CASE WHEN ACC.CURRENCY_ID = 11337947158805
                      THEN 0
                      ELSE BOOK.DEBIT_AMOUNT_BASE
                     END ) AS DEBIT_AMOUNT_BASE
                FROM 
                CM_BANK_ACCOUNT CB
                INNER JOIN CM_INV_BANK BOOK ON CB.ID = BOOK.BANK_ACCOUNT_ID
                INNER JOIN FIN_ACCOUNT ACC         ON CB .ACCOUNT_ID = ACC.ACCOUNT_ID
                LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = ACC.CURRENCY_ID
                LEFT JOIN FIN_CASH_FLOW_SUB_CATEGORY CF ON BOOK.CASH_FLOW_SUB_CATEGORY_ID = CF.CASH_FLOW_SUB_CATEGORY_ID
                LEFT JOIN
                 ( SELECT GLM.INVOICE_ID, GLM.GENERAL_LEDGER_ID,
                 LISTAGG( CASE WHEN GLO.CASH_FLOW_SUB_CATEGORY_CODE IS NOT NULL THEN ( GLO.CASH_FLOW_SUB_CATEGORY_NAME) ELSE NULL END,',') WITHIN GROUP ( ORDER BY GLO.CASH_FLOW_SUB_CATEGORY_ID) AS SUB_CATEGORY_NAME
                 FROM FIN_GENERAL_LEDGER_MAP GLM
                  INNER JOIN FIN_GENERAL_LEDGER_OPP GLO ON GLM.GENERAL_LEDGER_ID = GLO.GENERAL_LEDGER_ID
                  WHERE
                   GLO.BOOK_TYPE_ID NOT IN (1, 17) AND
                   GLM.OBJECT_ID = 20003
                  GROUP BY
                   GLM.INVOICE_ID,
                   GLM.GENERAL_LEDGER_ID
                 )
                 GLM ON ( GLM.INVOICE_ID = BOOK.ID )
                WHERE TRUNC(BOOK.BOOK_DATE) = TRUNC(sysdate-1) AND BOOK.IS_DEBIT=1
                GROUP BY CF.NAME,CB.BANK_ACCOUNT_DESC_L";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataBankByActivityModel() {
        $query = "SELECT
                TO_CHAR(BANK.BOOK_DATE, 'DD') AS REG_DATE,
                BANK.BOOK_DATE,
                COALESCE(SUM(BANK.DEBIT_AMOUNT), 0)  AS DEBIT_AMOUNT,
                COALESCE(SUM(BANK.CREDIT_AMOUNT), 0) AS CREDIT_AMOUNT
                FROM
                  CM_INV_BANK BANK 
               INNER JOIN
                ( SELECT START_DATE, END_DATE, PERIOD_NAME, ID FROM FIN_FISCAL_PERIOD WHERE TYPE_ID = 1 AND TO_CHAR(START_DATE, 'yyyy') = TO_CHAR(SYSDATE, 'yyyy')
                ) LINE ON BANK.BOOK_DATE BETWEEN LINE.START_DATE AND LINE.END_DATE
               WHERE
                TRUNC(SYSDATE) BETWEEN LINE.START_DATE AND LINE.END_DATE
               GROUP BY
                BANK.BOOK_DATE
               ORDER BY
                BANK.BOOK_DATE";

        return $this->db->GetAll($query);
    }    
    
    public function getDataSalesStoreByChannelTypeModel() {
        $query = "
            SELECT
                MAX(HDR.INVOICE_DATE)-1 INVOICE_DATE,
                SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT,
                SS.ID AS CHANNEL_ID,
                SS.NAME AS CHANNEL_NAME
                FROM
                SM_SALES_INVOICE_HEADER HDR
                INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID 
                INNER JOIN SDM_SALES_CHANNEL SS ON SS.ID = HDR.CHANNEL_ID
                WHERE 
                TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                AND (HDR.IS_REMOVED = 0 OR HDR.IS_REMOVED IS NULL) 
                AND (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL)
                And 
                HDR.STORE_ID = 1510117118100
                GROUP BY 
                SS.ID,
                SS.NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataSalesByChannelTypeModel() {
        $query = "
            SELECT
                MAX(HDR.INVOICE_DATE)-1 INVOICE_DATE,
                DTL.SECTION_ID AS CHANNEL_ID,
                SCT.NAME AS CHANNEL_NAME,
                SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
                FROM
                SM_SALES_INVOICE_HEADER HDR
                INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID 
                INNER JOIN SM_SECTION SCT ON DTL.SECTION_ID = SCT.SECTION_ID
                WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                AND (HDR.IS_REMOVED = 0 OR HDR.IS_REMOVED IS NULL) 
                AND (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL)
                AND HDR.STORE_ID = 1510117117628
                GROUP BY 
                DTL.SECTION_ID,
                SCT.NAME";
        
        return $this->db->GetAll($query);
    }    

    public function getDataRSalesByChannelTypeModel() {
        $query = "
            SELECT
                MAX(HDR.INVOICE_DATE)-1 INVOICE_DATE,
                DTL.SECTION_ID AS CHANNEL_ID,
                SCT.NAME AS CHANNEL_NAME,
                SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
                FROM
                SM_SALES_INVOICE_HEADER HDR
                INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID 
                INNER JOIN SM_SECTION SCT ON DTL.SECTION_ID = SCT.SECTION_ID
                WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                AND (HDR.IS_REMOVED = 0 OR HDR.IS_REMOVED IS NULL) 
                AND (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL)
                AND HDR.STORE_ID = 1525063362348
                GROUP BY 
                DTL.SECTION_ID,
                SCT.NAME";
        
        return $this->db->GetAll($query);
    }    

    public function getDataR2SalesByChannelTypeModel() {
        $query = "
            SELECT
                MAX(HDR.INVOICE_DATE)-1 INVOICE_DATE,
                DTL.SECTION_ID AS CHANNEL_ID,
                SCT.NAME AS CHANNEL_NAME,
                SUM(DTL.LINE_TOTAL_AMOUNT) AS TOTAL_AMOUNT
                FROM
                SM_SALES_INVOICE_HEADER HDR
                INNER JOIN SM_SALES_INVOICE_DETAIL DTL ON HDR.SALES_INVOICE_ID = DTL.SALES_INVOICE_ID 
                INNER JOIN SM_SECTION SCT ON DTL.SECTION_ID = SCT.SECTION_ID
                WHERE TRUNC(HDR.INVOICE_DATE) = TRUNC(SYSDATE) - 1
                AND (HDR.IS_REMOVED = 0 OR HDR.IS_REMOVED IS NULL) 
                AND (DTL.IS_REMOVED = 0 OR DTL.IS_REMOVED IS NULL)
                AND HDR.STORE_ID = 1525063362607
                GROUP BY 
                DTL.SECTION_ID,
                SCT.NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getOpenRolesModel() {
        $query = "
            SELECT COUNT (OD.DEPARTMENT_NAME) AS COUNT,
            OD.DEPARTMENT_NAME

            FROM ORG_DEPARTMENT OD
            INNER JOIN HRM_POSITION_KEY HPK ON HPK.DEPARTMENT_ID = OD.DEPARTMENT_ID 
            GROUP BY 
            od.department_name";
        
        return $this->db->GetAll($query);
    }    
    
    public function getFilledRolesModel() {
        $query = "
            SELECT COUNT (OD.DEPARTMENT_NAME) AS COUNT,
            OD.DEPARTMENT_NAME
            FROM ORG_DEPARTMENT OD
            INNER JOIN HRM_POSITION_KEY HPK ON HPK.DEPARTMENT_ID = OD.DEPARTMENT_ID 
            GROUP BY 
             OD.DEPARTMENT_NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataRecruitmentModel() {
        $query = "SELECT COUNT(OD.DEPARTMENT_NAME) AS COUNT_OPEN,
                COUNT(OD.DEPARTMENT_NAME) AS COUNT_FILLED,
                OD.DEPARTMENT_NAME
                FROM ORG_DEPARTMENT OD
                INNER JOIN HRM_POSITION_KEY HPK ON HPK.DEPARTMENT_ID = OD.DEPARTMENT_ID 
                GROUP BY 
                 OD.DEPARTMENT_NAME";

        return $this->db->GetAll($query);
    }    
    
    public function getDataHrmsCartModel() {
        $query = "
            SELECT DISTINCT COUNT(E.EMPLOYEE_ID)
                FROM HRM_EMPLOYEE E
                INNER JOIN HRM_EMPLOYEE_KEY EK
                ON EK.EMPLOYEE_ID = E.EMPLOYEE_ID
                AND EK.IS_ACTIVE  = 1
                LEFT JOIN ORG_DEPARTMENT DEP
                ON DEP.DEPARTMENT_ID = EK.DEPARTMENT_ID
                LEFT JOIN HRM_EMPLOYEE_STATUS STS
                ON STS.STATUS_ID = EK.STATUS_ID
                LEFT JOIN HRM_CURRENT_STATUS
                ON HRM_CURRENT_STATUS.ID = EK.CURRENT_STATUS_ID
                LEFT JOIN REF_GENDER G
                ON G.CODE = E.GENDER
                LEFT JOIN HRM_POSITION_KEY POSK
                ON POSK.POSITION_KEY_ID = EK.POSITION_KEY_ID
                LEFT JOIN HRM_POSITION POS
                ON POS.POSITION_ID = POSK.POSITION_ID
                LEFT JOIN VW_HCM_WORKED_YEAR WORKYEAR
                ON WORKYEAR.EMPLOYEE_ID = E.EMPLOYEE_ID
                WHERE E.IS_ACTIVE                     = 1
                AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')";
        $cart1Data = $this->db->GetOne($query);
        
        $query = "
            SELECT DISTINCT COUNT(E.EMPLOYEE_ID)
                FROM HRM_EMPLOYEE E
                INNER JOIN HRM_EMPLOYEE_KEY EK
                ON EK.EMPLOYEE_ID        =E.EMPLOYEE_ID
                AND EK.WORK_START_DATE  IS NOT NULL
                AND EK.WORK_END_DATE    IS NOT NULL
                AND EK.IS_ACTIVE         =0
                AND EK.CURRENT_STATUS_ID = 6
                AND EK.STATUS_ID         = 41
                LEFT JOIN
                  (SELECT LB.ID,
                    LB.BOOK_TYPE_ID,
                    LB.DESCRIPTION AS LABOUR_DESCRITION,
                    LBD.OLD_EMPLOYEE_KEY_ID,
                    LBD.QUITJOB_TYPE_ID,
                    LBD.PUNISHMENT_TYPE_ID,
                    LBD.DESCRIPTION,
                    LBD.START_DATE
                  FROM HCM_LABOUR_BOOK LB
                  INNER JOIN HCM_LABOUR_BOOK_DTL LBD
                  ON LB.ID = LBD.BOOK_ID
                  LEFT JOIN META_WFM_STATUS WS
                  ON LB.WFM_STATUS_ID           =WS.ID
                  AND WS.WFM_STATUS_CODE        ='APPROVE'
                  WHERE LB.BOOK_TYPE_ID        IN (9029, 9030, 9063)
                  AND ( LB.IS_FEEDBACK          = 0
                  OR LB.IS_FEEDBACK            IS NULL )
                  ) HR ON HR.OLD_EMPLOYEE_KEY_ID=EK.EMPLOYEE_KEY_ID
                LEFT JOIN ORG_DEPARTMENT DEP
                ON DEP.DEPARTMENT_ID = EK.DEPARTMENT_ID
                LEFT JOIN HRM_EMPLOYEE_STATUS STS
                ON STS.STATUS_ID = EK.STATUS_ID
                LEFT JOIN HRM_CURRENT_STATUS
                ON HRM_CURRENT_STATUS.ID=EK.CURRENT_STATUS_ID
                LEFT JOIN HRM_POSITION_KEY POSK
                ON POSK.POSITION_KEY_ID = EK.POSITION_KEY_ID
                LEFT JOIN HRM_POSITION POS
                ON POS.POSITION_ID = POSK.POSITION_ID
                LEFT JOIN REF_GENDER G
                ON G.CODE = E.GENDER
                LEFT JOIN CRM_CUSTOMER_MAP CCM
                ON CCM.EMPLOYEE_ID=E.EMPLOYEE_ID
                LEFT JOIN VW_HCM_WORKED_YEAR WORKYEAR
                ON WORKYEAR.EMPLOYEE_ID=E.EMPLOYEE_ID
                LEFT JOIN HRM_EMP_HOME_ADDRESS HA
                ON HA.EMPLOYEE_ID=E.EMPLOYEE_ID
                LEFT JOIN HRM_QUITJOB_TYPE QT
                ON HR.QUITJOB_TYPE_ID = QT.QUITJOB_TYPE_ID
                LEFT JOIN HRM_PUNISHMENT_TYPE PT
                ON HR.PUNISHMENT_TYPE_ID = PT.ID
                LEFT JOIN BOOK_TYPE BT
                ON HR.BOOK_TYPE_ID = BT.BOOK_TYPE_ID
                LEFT JOIN
                  (SELECT employee_id,
                    is_active
                  FROM hrm_employee_key
                  WHERE is_active=1
                  ) emp
                ON emp.employee_id           =e.employee_id
                WHERE  TO_CHAR(HR.START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')";
        $cart2Data = $this->db->GetOne($query);
        
        $query = "
            SELECT COUNT(CAN.CANDIDATE_ID)
              FROM hrm_candidate_key ckey
              INNER JOIN hrm_candidate can
              ON can.candidate_id=ckey.candidate_id
              LEFT JOIN HRM_POSITION POS1
              ON POS1.POSITION_ID = CKEY.POSITION_ID
              WHERE TO_CHAR(CKEY.CREATED_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')";
        $cart3Data = $this->db->GetOne($query);
        
        $query = "
            SELECT COUNT(LB.ID)
                FROM HCM_LABOUR_BOOK LB
              INNER JOIN HCM_LABOUR_BOOK_DTL BD
              ON BD.BOOK_ID=LB.ID
              LEFT JOIN HRM_EMPLOYEE_KEY EK
              ON
                CASE
                  WHEN EK.WORK_START_DATE IS NOT NULL
                  AND EK.WORK_END_DATE    IS NOT NULL
                  AND LB.BOOK_DATE BETWEEN EK.WORK_START_DATE AND EK.WORK_END_DATE
                  THEN EK.EMPLOYEE_KEY_ID
                  WHEN EK.WORK_START_DATE IS NOT NULL
                  AND EK.WORK_END_DATE    IS NULL
                  THEN EK.EMPLOYEE_KEY_ID
                END = BD.OLD_EMPLOYEE_KEY_ID
              LEFT JOIN HRM_EMPLOYEE E
              ON E.EMPLOYEE_ID=EK.EMPLOYEE_ID
              LEFT JOIN HCM_RECTORSHIP HR
              ON HR.ID=LB.RECTORSHIP_ID
              LEFT JOIN BOOK_TYPE BT
              ON BT.BOOK_TYPE_ID=LB.BOOK_TYPE_ID
              WHERE (LB.IS_FEEDBACK    = 0
              OR LB.IS_FEEDBACK       IS NULL)
              AND BT.PARENT_ID         = 9034
              AND TO_CHAR(BD.START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')";
        $cart4Data = $this->db->GetOne($query);
        
        return array(
            'cart1Data' => $cart1Data,
            'cart2Data' => $cart2Data,
            'cart3Data' => $cart3Data,
            'cart4Data' => $cart4Data,
        );
    }    
    
    public function getDataHrmsListModel() {
        $query = "SELECT EK.STATUS_ID,
                    HES.STATUS_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                  FROM HRM_EMPLOYEE_KEY EK
                  INNER JOIN HRM_EMPLOYEE E
                  ON EK.EMPLOYEE_ID=E.EMPLOYEE_ID
                  INNER JOIN HRM_EMPLOYEE_STATUS HES
                  ON EK.STATUS_ID=HES.STATUS_ID
                  WHERE HES.IS_ACTIVE   = 1
                  AND EK.IS_ACTIVE      =1
                  AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                  GROUP BY HES.STATUS_NAME,
                    EK.STATUS_ID";

        return $this->db->GetAll($query);
    }    
    
    public function getDataHrmsByTypeModel() {
        $query = "SELECT 
                    EK.CURRENT_STATUS_ID,
                    HCS.NAME||' - өмнөх саруудад' AS CURRRENT_STATUS_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                   FROM HRM_EMPLOYEE_KEY EK
                   INNER JOIN HRM_CURRENT_STATUS HCS
                   ON EK.CURRENT_STATUS_ID = HCS.ID
                   INNER JOIN HRM_EMPLOYEE E
                   ON E.EMPLOYEE_ID   =EK.EMPLOYEE_ID
                   LEFT JOIN
                   (SELECT 
                    EK.CURRENT_STATUS_ID,
                    HCS.NAME                  AS CURRRENT_STATUS_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                   FROM HRM_EMPLOYEE_KEY EK
                   INNER JOIN HRM_CURRENT_STATUS HCS
                   ON EK.CURRENT_STATUS_ID = HCS.ID
                   INNER JOIN HRM_EMPLOYEE E
                   ON E.EMPLOYEE_ID   =EK.EMPLOYEE_ID
                   WHERE EK.IS_ACTIVE = 1
                   AND E.IS_ACTIVE    =1
                   AND HCS.CODE      IN ('01','02','03','04','05')
                   AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                   GROUP BY EK.CURRENT_STATUS_ID,
                    HCS.NAME)BB ON BB.CURRENT_STATUS_ID=EK.CURRENT_STATUS_ID
                    WHERE EK.IS_ACTIVE = 1
                   AND E.IS_ACTIVE    =1
                   AND HCS.CODE      IN ('01','02','03','04','05')
                   AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') <> TO_CHAR(SYSDATE,'YYYY-MM')
                    GROUP BY EK.CURRENT_STATUS_ID,
                    HCS.NAME,
                    BB.CNT

                    UNION ALL

                    SELECT 
                    EK.CURRENT_STATUS_ID,
                    HCS.NAME||' - энэ сард' AS CURRRENT_STATUS_NAME,
                    NVL(BB.CNT,0) AS CNT
                   FROM HRM_EMPLOYEE_KEY EK
                   INNER JOIN HRM_CURRENT_STATUS HCS
                   ON EK.CURRENT_STATUS_ID = HCS.ID
                   INNER JOIN HRM_EMPLOYEE E
                   ON E.EMPLOYEE_ID   =EK.EMPLOYEE_ID
                   LEFT JOIN
                   (SELECT 
                    EK.CURRENT_STATUS_ID,
                    HCS.NAME                  AS CURRRENT_STATUS_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                   FROM HRM_EMPLOYEE_KEY EK
                   INNER JOIN HRM_CURRENT_STATUS HCS
                   ON EK.CURRENT_STATUS_ID = HCS.ID
                   INNER JOIN HRM_EMPLOYEE E
                   ON E.EMPLOYEE_ID   =EK.EMPLOYEE_ID
                   WHERE EK.IS_ACTIVE = 1
                   AND E.IS_ACTIVE    =1
                   AND HCS.CODE      IN ('01','02','03','04','05')
                   AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                   GROUP BY EK.CURRENT_STATUS_ID,
                    HCS.NAME)BB ON BB.CURRENT_STATUS_ID=EK.CURRENT_STATUS_ID
                    WHERE EK.IS_ACTIVE = 1
                   AND E.IS_ACTIVE    =1
                   AND HCS.CODE      IN ('01','02','03','04','05')
                   AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') <> TO_CHAR(SYSDATE,'YYYY-MM')
                    GROUP BY EK.CURRENT_STATUS_ID,
                    HCS.NAME,
                    BB.CNT";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataHrmsByDepartmentModel() {
        $query = "SELECT EK.DEPARTMENT_ID,
                    od.DEPARTMENT_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS ALL_CNT,
                    NVL(AA.CNT,0) AS NEW_CNT
                  FROM ORG_DEPARTMENT OD
                  LEFT JOIN HRM_EMPLOYEE_KEY EK
                  ON OD.DEPARTMENT_ID=EK.DEPARTMENT_ID
                  INNER JOIN HRM_EMPLOYEE E
                  ON EK.EMPLOYEE_ID  =E.EMPLOYEE_ID
                  LEFT JOIN 
                  (SELECT ek.DEPARTMENT_ID,
                    OD.DEPARTMENT_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                  FROM ORG_DEPARTMENT OD
                  LEFT JOIN  HRM_EMPLOYEE_KEY EK
                  ON OD.DEPARTMENT_ID=EK.DEPARTMENT_ID
                  INNER JOIN HRM_EMPLOYEE E
                  ON EK.EMPLOYEE_ID  =E.EMPLOYEE_ID
                  WHERE EK.IS_ACTIVE = 1
                  AND E.IS_ACTIVE    =1
                  AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                  GROUP BY ek.DEPARTMENT_ID,
                    od.DEPARTMENT_NAME) AA ON AA.DEPARTMENT_ID = EK.DEPARTMENT_ID
                   WHERE EK.IS_ACTIVE = 1
                  AND E.IS_ACTIVE    =1
                  AND TO_CHAR(E.WORK_START_DATE,'YYYY-MM') <> TO_CHAR(SYSDATE,'YYYY-MM')
                  GROUP BY ek.DEPARTMENT_ID,
                    od.DEPARTMENT_NAME,
                    AA.CNT";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataTimeByDepartmentModel() {
        $query = "SELECT 
                    P.DEPARTMENT_ID,
                    P.DEPARTMENT_NAME,
                    P.PLAN_TIME,
                    W.WORKED_CLEAN_TIME
                 FROM (
                    SELECT 
                        SUM(B1.PLAN_TIME) AS PLAN_TIME,
                        B1.DEPARTMENT_ID,
                        DEP.DEPARTMENT_NAME
                    FROM (
                        SELECT 
                            FNC_TMS_GET_PLAN_TIME(B.PLAN_ID) / 60 AS PLAN_TIME,
                            DEPARTMENT_ID
                        FROM (
                            SELECT DISTINCT
                                D.PLAN_ID,
                                D.PLAN_DATE,
                                K.DEPARTMENT_ID,
                                K.EMPLOYEE_ID
                            FROM
                                TMS_EMPLOYEE_TIME_PLAN_DTL D
                                INNER JOIN TMS_EMPLOYEE_TIME_PLAN_HDR H ON H.ID = D.TIME_PLAN_ID
                                INNER JOIN (
                                    SELECT DISTINCT DEPARTMENT_ID, EMPLOYEE_ID FROM HRM_EMPLOYEE_KEY WHERE IS_ACTIVE = 1
                                )K ON K.EMPLOYEE_ID = H.EMPLOYEE_ID
                            WHERE TRUNC(D.PLAN_DATE) BETWEEN '".Date::currentDate('Y-m')."-01' AND '".Date::currentDate('Y-m-d')."'
                        )B
                    )B1 
                    INNER JOIN ORG_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = B1.DEPARTMENT_ID
                    GROUP BY 
                    B1.DEPARTMENT_ID, DEP.DEPARTMENT_NAME
                 ) P
                 LEFT JOIN (
                    SELECT
                        K.DEPARTMENT_ID,
                        ROUND(SUM(NVL(CLEAN_TIME,0) ) / 60,2) AS WORKED_CLEAN_TIME
                    FROM
                        TNA_TIME_BALANCE_HDR H
                        INNER JOIN HRM_EMPLOYEE HE ON HE.EMPLOYEE_ID = H.EMPLOYEE_ID
                        INNER JOIN (
                            SELECT DISTINCT
                                DEPARTMENT_ID,
                                EMPLOYEE_ID
                            FROM
                                HRM_EMPLOYEE_KEY
                            WHERE
                                IS_ACTIVE = 1
                        ) K ON K.EMPLOYEE_ID = H.EMPLOYEE_ID
                    WHERE
                        TRUNC(BALANCE_DATE) BETWEEN '".Date::currentDate('Y-m')."-01' AND '".Date::currentDate('Y-m-d')."'
                        AND   IS_CONFIRMED = 1
                    GROUP BY
                        K.DEPARTMENT_ID
                 )W ON P.DEPARTMENT_ID = W.DEPARTMENT_ID";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataHrmsByRegimenModel() {
        $query = "SELECT 
                    LB.BOOK_TYPE_ID,
                    BT.BOOK_TYPE_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                    FROM HCM_LABOUR_BOOK LB
                    LEFT JOIN BOOK_TYPE BT
                    ON LB.BOOK_TYPE_ID=BT.BOOK_TYPE_ID
                    LEFT JOIN HCM_LABOUR_BOOK_DTL LBD
                    ON LB.ID=LBD.BOOK_ID
                    INNER JOIN HRM_EMPLOYEE_KEY EK
                    ON LBD.OLD_EMPLOYEE_KEY_ID=EK.EMPLOYEE_KEY_ID
                    WHERE LB.BOOK_TYPE_ID IN (9034,9035,9036,9037)
                    AND TO_CHAR(LBD.START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                    GROUP BY
                    LB.BOOK_TYPE_ID,
                    BT.BOOK_TYPE_NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataHrmsByPensionModel() {
        $query = "SELECT 
                    LB.BOOK_TYPE_ID,
                    BT.BOOK_TYPE_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                    FROM HCM_LABOUR_BOOK LB
                    LEFT JOIN BOOK_TYPE BT
                    ON LB.BOOK_TYPE_ID=BT.BOOK_TYPE_ID
                    LEFT JOIN HCM_LABOUR_BOOK_DTL LBD
                    ON LB.ID=LBD.BOOK_ID
                    INNER JOIN HRM_EMPLOYEE_KEY EK
                    ON LBD.OLD_EMPLOYEE_KEY_ID=EK.EMPLOYEE_KEY_ID
                    WHERE BT.PARENT_ID = 9038
                    AND TO_CHAR(LBD.START_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                    GROUP BY
                    LB.BOOK_TYPE_ID,
                    BT.BOOK_TYPE_NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataHrmsByWorkOutModel() {
        $query = "SELECT ek.DEPARTMENT_ID,
                    od.DEPARTMENT_NAME,
                    COUNT(EK.EMPLOYEE_KEY_ID) AS CNT
                  FROM HRM_EMPLOYEE_KEY EK
                  INNER JOIN org_department od
                  ON od.department_id=ek.department_id
                  INNER JOIN hrm_employee e
                  ON ek.employee_id  =e.employee_id
                  WHERE EK.IS_ACTIVE = 0
                  AND E.IS_ACTIVE    =0 and ek.status_id=41 and ek.current_status_id=6 and ek.work_end_date is not null
                  AND TO_CHAR(EK.WORK_END_DATE,'YYYY-MM') = TO_CHAR(SYSDATE,'YYYY-MM')
                  GROUP BY ek.DEPARTMENT_ID,
                    od.DEPARTMENT_NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataBankOutcomeModel() {
        $query = "SELECT
                CB.BANK_ACCOUNT_DESC_L,
                CF.NAME AS SUB_CATEGORY_NAME,
                SUM(BOOK.CREDIT_AMOUNT ) AS CREDIT_AMOUNT,
                SUM(
                CASE WHEN ACC.CURRENCY_ID = 11337947158805
                 THEN 0
                 ELSE BOOK.CREDIT_AMOUNT_BASE
                END ) AS CREDIT_AMOUNT_BASE
               FROM
                CM_BANK_ACCOUNT CB
               INNER JOIN CM_INV_BANK BOOK             ON CB.ID = BOOK.BANK_ACCOUNT_ID
               INNER JOIN FIN_ACCOUNT ACC              ON CB .ACCOUNT_ID = ACC.ACCOUNT_ID
               LEFT JOIN REF_CURRENCY CURR             ON CURR.CURRENCY_ID = ACC.CURRENCY_ID
               LEFT JOIN FIN_CASH_FLOW_SUB_CATEGORY CF ON BOOK.CASH_FLOW_SUB_CATEGORY_ID = CF.CASH_FLOW_SUB_CATEGORY_ID
               LEFT JOIN
                ( SELECT GLM.INVOICE_ID, GLM.GENERAL_LEDGER_ID, LISTAGG( CASE WHEN GLO.CASH_FLOW_SUB_CATEGORY_CODE IS NOT NULL THEN ( GLO.CASH_FLOW_SUB_CATEGORY_NAME) ELSE NULL END,',') WITHIN GROUP ( ORDER BY GLO.CASH_FLOW_SUB_CATEGORY_ID) AS SUB_CATEGORY_NAME FROM FIN_GENERAL_LEDGER_MAP GLM
                 INNER JOIN FIN_GENERAL_LEDGER_OPP GLO ON GLM.GENERAL_LEDGER_ID = GLO.GENERAL_LEDGER_ID
                 WHERE
                  GLO.BOOK_TYPE_ID NOT IN (1, 17) AND
                  GLM.OBJECT_ID = 20003
                 GROUP BY
                  GLM.INVOICE_ID,
                  GLM.GENERAL_LEDGER_ID
                )
                GLM ON ( GLM.INVOICE_ID = BOOK.ID )
               WHERE
                TRUNC(BOOK.BOOK_DATE) = TRUNC(sysdate - 1) AND
                BOOK.IS_DEBIT = 0
               GROUP BY
                CB.BANK_ACCOUNT_DESC_L,
                CF.NAME";
        
        return $this->db->GetAll($query);
    }    
    
    public function getDataCart1SalaryModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1484913258767',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y').'-01-01'
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y-m-d')
                    )
                )
            )
        );
        
        $resultArr = array();
        $sumVar = 0;
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];            
            
            foreach($resultArr as $rowVal)
                $sumVar += (float) $rowVal['f188'];
        }
        
        return $sumVar;
    }    
    
    public function getDataCart2SalaryModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1484913259942',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y').'-01-01'
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y-m-d')
                    )
                )
            )
        );
        
        $resultArr = array();
        $sumVar = 0;
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];            
            
            foreach($resultArr as $rowVal)
                $sumVar += (float) $rowVal['f199'];
        }
        
        return $sumVar;
    }    
    
    public function getDataCart3SalaryModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1485257852228378',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y').'-01-01'
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y-m-d')
                    )
                )
            )
        );
        
        $resultArr = array();
        $sumVar = 0;
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];            
            
            foreach($resultArr as $rowVal)
                $sumVar += (float) $rowVal['f142'];
        }
        
        return $sumVar;
    }    
    
    public function getDataCart4SalaryModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1485258920120079',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y').'-01-01'
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y-m-d')
                    )
                )
            )
        );
        
        $resultArr = array();
        $sumVar = 0;
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];            
            
            foreach($resultArr as $rowVal)
                $sumVar += (float) $rowVal['f308'];
        }
        
        return $sumVar;
    }    
    
    public function getDataHhoatSalaryModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1479888487343',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'year' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y')
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function getDataRestYearSalaryModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1481012451208',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'year' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y')
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function getDataSalaryByPensionModel() {
        (Array) $param = array(
            'systemMetaGroupId' => '1481012450523',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'year' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => Date::currentDate('Y')
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function getDataSalaryByPension1Model() {
        (Array) $param = array(
            'systemMetaGroupId' => '1481012450732',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'year' =>  array(
                    array(
                        'operator' => '=',
//                        'operand' => Date::currentDate('Y')
                        'operand' => '2017'
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        unset($data['result']['paging']);
        unset($data['result']['aggregatecolumns']);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list1Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519721337095',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }            
    
    public function list22Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519610623527',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list3Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519443693246820',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list24Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519445983606',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list25Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634335856',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list31Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634335856',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list41Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634337859',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list42Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519721341171',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list51Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634399210',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list52Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634399492',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list53Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634399432',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list54Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634338261',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list55Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519634338302',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list61Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519721345912',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list62Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519721371126',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list72Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519721371867',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list82Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519701906097',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list91Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519715762071',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function list92Model($startDate, $endDate) {
        (Array) $param = array(
            'systemMetaGroupId' => '1519721379153',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                )
            )            
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function sendMailModel() {
            
        $emailTo = Input::post('emailTo');
        $emailSubject = Input::post('emailSubject');
        $emailBody = html_entity_decode(Input::post('emailBody'));
        $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();

        $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        
        $emailBodyImage = '<br /><br /><img src="'.Input::post('base64image').'">';
        $emailBodyContent = str_replace('{htmlTable}', $emailBody.$emailBodyImage, $emailBodyContent);

        $emailFrom = EMAIL_FROM;        
        $emailFromName = EMAIL_FROM_NAME;

        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
        includeLib('PDF/Pdf');
        
        if(Input::post('emailSendType') === 'pdf') {
            $pdf = Pdf::createSnappyPdf();

            $tempPdfFileName = 'temp-erp-pdf-' . getUID();
            Pdf::generateFromHtml($pdf, $emailBodyContent, $tmp_dir.'/'.$tempPdfFileName);
            
            $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
            $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyContent);
        }

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;

        if (!defined('SMTP_USER')) {

            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->setFrom($emailFrom, $emailFromName); 
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->msgHTML($emailBodyContent);
        $mail->AltBody = 'Veritech ERP - ' . $emailSubject;

        $emailList = array();

        if (is_array($emailTo)) {
            $emailToArr = array_unique($emailTo);
        } else {
            if (strpos($emailTo, ',') !== false) {
                $emailToArr = array_map('trim', explode(',', rtrim($emailTo, ',')));
            } else {
                $emailToArr = array_map('trim', explode(';', rtrim($emailTo, ';')));
            }
        }   

        $response = array('status' => 'success', 'message' => 'Амжилттай илгээгдлээ');

        if (count($emailToArr)) {

            $emailList = $emailToArr;
            $emailToCc = Input::post('emailToCc');

            if (!empty($emailToCc)) {

                if (strpos($emailToCc, ',') !== false) {
                    $emailToCcArr = array_map('trim', explode(',', rtrim($emailToCc, ',')));
                } else {
                    $emailToCcArr = array_map('trim', explode(';', rtrim($emailToCc, ';')));
                }

                $emailList = array_merge_recursive($emailList, $emailToCcArr);
            }

            $emailToBcc = Input::post('emailToBcc');

            if (!empty($emailToBcc)) {

                if (strpos($emailToBcc, ',') !== false) {
                    $emailToBccArr = array_map('trim', explode(',', rtrim($emailToBcc, ',')));
                } else {
                    $emailToBccArr = array_map('trim', explode(';', rtrim($emailToBcc, ';')));
                }

                $emailList = array_merge_recursive($emailList, $emailToBccArr);
            }

            $emailList = array_unique($emailList);
            
            $this->load->model('mddatamodel', 'middleware/models/');
            
            foreach ($emailList as $email) {

                if (!empty($email)) {
                    $mail->addAddress($email);
                    
                    if(isset($tempPdfFileName))
                        $mail->AddAttachment($tmp_dir . '/'.$tempPdfFileName.'.pdf', 'HRMS_ACTIVITY_REPORT', 'base64', 'application/pdf');

                    if (!$mail->send()) {
                        $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                    }

                    $mail->clearAllRecipients();
                }
            }
            
            if(isset($tempPdfFileName))
                @unlink($tmp_dir . '/'.$tempPdfFileName.'.pdf');
        }

        return $response;
    }

    public function sumaCart1Model() {
        $query = "
            SELECT 
                sum(d.fact_2) as order_qty
                FROM ic_invoice_book b
                inner join ic_invoice_book_dtl d on b.id = d.invoice_Book_id
                inner join im_item ii on d.dim_1 = ii.item_id
                where B.BOOK_TYPE_ID = 40000008
                and to_char(b.book_date,'YYYY-MM-DD') between TRUNC(SYSDATE-1) and TRUNC(SYSDATE-1)";
        
        return $this->db->GetOne($query);
    }    

    public function sumaCart2Model() {
        $query = "
                SELECT 
                    sum(d.fact_3 * d.fact_2) as total_amount
                    FROM ic_invoice_book b
                    inner join ic_invoice_book_dtl d on b.id = d.invoice_Book_id
                    inner join im_item ii on d.dim_1 = ii.item_id
                    where B.BOOK_TYPE_ID = 40000008
                    and to_char(b.book_date,'YYYY-MM-DD') between TRUNC(SYSDATE-1) and TRUNC(SYSDATE-1)";
        
        return $this->db->GetOne($query);
    }    

    public function sumaCart3Model() {
        $query = "
            SELECT 
            DISTINCT
            sum(ibd.in_qty) as in_qty
            FROM IC_INVOICE_BOOK IB
            INNER JOIN IC_INVOICE_BOOK_DTL BD ON IB.ID = BD.INVOICE_BOOK_ID
            INNER JOIN META_DM_RECORD_MAP MP ON BD.ID = MP.SRC_RECORD_ID AND LOWER(MP.SRC_TABLE_NAME) = 'ic_invoice_book_dtl'
            inner JOIN (
            SELECT
            IK.ITEM_BOOK_ID,
            IBD.REF_ITEM_ID,
            SUM(IBD.IN_QTY) AS IN_QTY,
            ik.book_date,
            ibd.unit_vat_purchase_price
            FROM IM_ITEM_BOOK IK 
            INNER JOIN IM_ITEM_BOOK_DTL IBD ON IK.ITEM_BOOK_ID = IBD.ITEM_BOOK_ID
            WHERE IK.BOOK_TYPE_ID = 8
            GROUP BY IK.ITEM_BOOK_ID,
            IBD.REF_ITEM_ID, ik.book_date, ibd.unit_vat_purchase_price
            )IBD ON MP.TRG_RECORD_ID = IBD.ITEM_BOOK_ID and bd.dim_1 = ibd.ref_item_id
            inner join im_item ii on ibd.ref_item_id = ii.item_id
            and to_char(ibd.book_date,'YYYY-MM-DD') between TRUNC(SYSDATE-1) and TRUNC(SYSDATE-1)";
        
        return $this->db->GetOne($query);
    }    

    public function sumaCart4Model() {
        $query = "
            SELECT 
            DISTINCT
            sum(ibd.unit_vat_purchase_price * ibd.in_qty) as total_amount
            FROM IC_INVOICE_BOOK IB
            INNER JOIN IC_INVOICE_BOOK_DTL BD ON IB.ID = BD.INVOICE_BOOK_ID
            INNER JOIN META_DM_RECORD_MAP MP ON BD.ID = MP.SRC_RECORD_ID AND LOWER(MP.SRC_TABLE_NAME) = 'ic_invoice_book_dtl'
            inner JOIN (
            SELECT
            IK.ITEM_BOOK_ID,
            IBD.REF_ITEM_ID,
            SUM(IBD.IN_QTY) AS IN_QTY,
            ik.book_date,
            ibd.unit_vat_purchase_price
            FROM IM_ITEM_BOOK IK 
            INNER JOIN IM_ITEM_BOOK_DTL IBD ON IK.ITEM_BOOK_ID = IBD.ITEM_BOOK_ID
            WHERE IK.BOOK_TYPE_ID = 8
            GROUP BY IK.ITEM_BOOK_ID,
            IBD.REF_ITEM_ID, ik.book_date, ibd.unit_vat_purchase_price
            )IBD ON MP.TRG_RECORD_ID = IBD.ITEM_BOOK_ID and bd.dim_1 = ibd.ref_item_id
            inner join im_item ii on ibd.ref_item_id = ii.item_id
            and to_char(ibd.book_date,'YYYY-MM-DD') between TRUNC(SYSDATE-1) and TRUNC(SYSDATE-1)";
        
        return $this->db->GetOne($query);
    }    

    public function sumaPie5Model() {
        $query = "
            SELECT 
            IIC.ITEM_CATEGORY_NAME,
            IIC.ITEM_CATEGORY_ID,
            SUM(D.FACT_3 * D.FACT_2) AS TOTAL_AMOUNT
            FROM IC_INVOICE_BOOK B
            INNER JOIN IC_INVOICE_BOOK_DTL D ON B.ID = D.INVOICE_BOOK_ID
            INNER JOIN IM_ITEM II ON D.DIM_1 = II.ITEM_ID
            LEFT JOIN IM_ITEM_CATEGORY IIC ON II.ITEM_CATEGORY_ID = IIC.ITEM_CATEGORY_ID
            WHERE B.BOOK_TYPE_ID = 40000008
            AND TO_CHAR(B.BOOK_DATE,'YYYY-MM-DD') BETWEEN TRUNC(SYSDATE-1) AND TRUNC(SYSDATE-1)
            GROUP BY 
            IIC.ITEM_CATEGORY_NAME,
            IIC.ITEM_CATEGORY_ID";
        
        return $this->db->GetAll($query);
    }    

    public function sumaPie6Model() {
        $query = "
            SELECT 
            CC.CUSTOMER_ID,
            CC.CUSTOMER_NAME,
            SUM(D.FACT_3 * D.FACT_2) AS TOTAL_AMOUNT
            FROM IC_INVOICE_BOOK B
            INNER JOIN IC_INVOICE_BOOK_DTL D ON B.ID = D.INVOICE_BOOK_ID
            INNER JOIN IM_ITEM II ON D.DIM_1 = II.ITEM_ID
            INNER JOIN CRM_CUSTOMER CC ON B.DIM_1 = CC.CUSTOMER_ID
            WHERE B.BOOK_TYPE_ID = 40000008
            AND TO_CHAR(B.BOOK_DATE,'YYYY-MM-DD') BETWEEN TRUNC(SYSDATE-1) AND TRUNC(SYSDATE-1)
            GROUP BY 
            CC.CUSTOMER_ID,
            CC.CUSTOMER_NAME";
        
        return $this->db->GetAll($query);
    }    
    
    function depNames($ids) {
        if($ids)
            return $this->db->GetRow("SELECT LISTAGG(DEPARTMENT_NAME, ', ') WITHIN GROUP (ORDER BY DEPARTMENT_NAME) AS DEP_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID IN ($ids)");
        else
            return false;
    }
    
    public function baseHrmUzModel($dvId, $startDate, $endDate, $depId) {
        (Array) $param = array(
            'systemMetaGroupId' => $dvId,
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'filterStartDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $startDate
                    )
                ),
                'filterEndDate' =>  array(
                    array(
                        'operator' => '=',
                        'operand' => $endDate
                    )
                ),
                'filterDepartmentId' =>  array(
                    array(
                        'operator' => 'IN',
                        'operand' => $depId
                    )
                )
            )
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        return $resultArr;
    }    
}