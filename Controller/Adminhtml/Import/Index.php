<?php

namespace Prymag\ReviewsImporter\Controller\Adminhtml\Import;

class Index extends \Magento\Backend\App\Action {
    /**
        * @var \Magento\Framework\View\Result\PageFactory
        */
        protected $resultPageFactory;

        /**
         * Constructor
         *
         * @param \Magento\Backend\App\Action\Context $context
         * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
         */
        public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory
        ) {
            parent::__construct($context);
            $this->resultPageFactory = $resultPageFactory;
        }

        /**
         * @return \Magento\Framework\View\Result\Page
         */
        public function execute()
        {

            if( isset($this->getRequest()->getParams()['download_sample']) ){
                $heading = array(
                    'ID',
                    'PRODUCT',
                    'EMAIL',
                    'NICKNAME',
                    'RATING',
                    'TITLE',
                    'DETAIL',
                    'DATE',
                    'STATUS'
                );
                
                $filename = 'review_importer_sample.csv';
                $handle = fopen( $filename , 'w');
                fputcsv($handle, $heading);

                $data = $this->getSampleData();
                foreach($data as $d){
                    fputcsv($handle, $d);
                }

                $this->downloadCsv( $filename );
            }
            
            $this->messageManager->addNotice( 'Date format on the sample CSV file is MM/DD/YYYY, For status column use {1 = Approved, 2 = Pending, 3 = Not Approved} <br/> Please report issues to <a href="https://github.com/perrymarkg/m2-reviews-importer/issues">here</a>' );

            return  $resultPage = $this->resultPageFactory->create();
        }

        public function downloadCsv( $filename ){
            if (file_exists($filename)) {
                //set appropriate headers
                header('Content-Description: File Transfer');
                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename='.basename($filename));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));
                ob_clean();
                flush();
                readfile($filename);
            }
        }

        public function getSampleData(){
            $data = array(
                array(
                    '1',
                    '13',
                    'hasemail@mail.com',
                    'Emily',
                    '2',
                    'Not Good Enough!',
                    'Missing something',
                    '08/13/2016',
                    '1'
                ),
                array(
                    '2',
                    '50',
                    'roni_cost@example.com',
                    'Roni',
                    '5',
                    'Amazing!',
                    'Excellent product',
                    '12/13/2017',
                    '2'
                ),
                array(
                    '3',
                    '243',
                    '',
                    'Jamie',
                    '3',
                    'Almost!',
                    'Would have given it 5 stars if not for the damage',
                    '12/25/2017',
                    '3'
                ),
            );
            return $data;
        }
}