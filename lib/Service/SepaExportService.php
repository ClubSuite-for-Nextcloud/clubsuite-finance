<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Service;

use DOMDocument;
use OCA\ClubSuiteFinance\Db\Transaction;
use OCP\IDBConnection;

class SepaExportService {

    private IDBConnection $db;

    public function __construct(IDBConnection $db) {
        $this->db = $db;
    }

    public function generateSepaXml(array $transactions): string {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $root = $doc->createElement('Document');
        $root->setAttribute('xmlns', 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $doc->appendChild($root);

        $cstmrCdtTrfInitn = $doc->createElement('CstmrCdtTrfInitn');
        $root->appendChild($cstmrCdtTrfInitn);

        // GrpHdr
        $grpHdr = $doc->createElement('GrpHdr');
        $cstmrCdtTrfInitn->appendChild($grpHdr);
        $grpHdr->appendChild($doc->createElement('MsgId', 'MSG-' . time()));
        $grpHdr->appendChild($doc->createElement('CreDtTm', date('Y-m-d\TH:i:s')));
        $grpHdr->appendChild($doc->createElement('NbOfTxs', (string)count($transactions)));
        
        // Calculate Control Sum
        $sum = 0;
        foreach ($transactions as $tx) {
             // Assuming amount is in Cents, but XML needs 0.00 format usually
             $sum += $tx->getAmount();
        }
        $grpHdr->appendChild($doc->createElement('CtrlSum', number_format($sum / 100, 2, '.', '')));
        
        $initgPty = $doc->createElement('InitgPty');
        $grpHdr->appendChild($initgPty);
        $initgPty->appendChild($doc->createElement('Nm', 'Verein Online')); // Should come from Config

        // PmtInf
        $pmtInf = $doc->createElement('PmtInf');
        $cstmrCdtTrfInitn->appendChild($pmtInf);
        $pmtInf->appendChild($doc->createElement('PmtInfId', 'PMT-' . time()));
        $pmtInf->appendChild($doc->createElement('PmtMtd', 'TRF')); // Transfer
        $pmtInf->appendChild($doc->createElement('BtchBookg', 'true'));
        $pmtInf->appendChild($doc->createElement('NbOfTxs', (string)count($transactions)));
        $pmtInf->appendChild($doc->createElement('CtrlSum', number_format($sum / 100, 2, '.', '')));

        // Execution Date
        $pmtInf->appendChild($doc->createElement('ReqdExctnDt', date('Y-m-d')));

        // Debtor (The Club)
        $dbtr = $doc->createElement('Dbtr');
        $pmtInf->appendChild($dbtr);
        $dbtr->appendChild($doc->createElement('Nm', 'Der Verein'));
        
        $dbtrAcct = $doc->createElement('DbtrAcct');
        $pmtInf->appendChild($dbtrAcct);
        $id = $doc->createElement('Id');
        $dbtrAcct->appendChild($id);
        $id->appendChild($doc->createElement('IBAN', 'DE12345678901234567890')); // Dummy Club IBAN

        $dbtrAgt = $doc->createElement('DbtrAgt');
        $pmtInf->appendChild($dbtrAgt);
        $finInstnId = $doc->createElement('FinInstnId');
        $dbtrAgt->appendChild($finInstnId);
        $finInstnId->appendChild($doc->createElement('BIC', 'GENODED1DEF')); // Dummy Club BIC

        $pmtInf->appendChild($doc->createElement('ChrgBr', 'SLEV'));

        // Transactions
        foreach ($transactions as $tx) {
            /** @var Transaction $tx */
            $memberId = $tx->getMemberId();
            // Fetch IBAN logic would go here. For now, using placeholder or fetching if possible
            // Using DB connection to fetch member name/iban for the demo
            $memberName = 'Unknown Member';
            $memberIban = 'DE00000000000000000000'; 
            
            if ($memberId) {
                // Quick fetch (in real app, use Service/Mapper from Core)
                $qb = $this->db->getQueryBuilder();
                $qb->select('*')->from('clubsuite_members')->where($qb->expr()->eq('id', $qb->createNamedParameter($memberId)));
                $cursor = $qb->executeQuery();
                $row = $cursor->fetch();
                if ($row) {
                    $memberName = $row['firstname'] . ' ' . $row['lastname'];
                    $memberIban = $row['iban'] ?? 'DE00...';
                }
            }

            $cdtTrfTxInf = $doc->createElement('CdtTrfTxInf');
            $pmtInf->appendChild($cdtTrfTxInf);

            // PmtId
            $pmtId = $doc->createElement('PmtId');
            $cdtTrfTxInf->appendChild($pmtId);
            $pmtId->appendChild($doc->createElement('EndToEndId', 'TX-' . $tx->getId()));

            // Amt
            $amt = $doc->createElement('Amt');
            $cdtTrfTxInf->appendChild($amt);
            $instdAmt = $doc->createElement('InstdAmt', number_format($tx->getAmount() / 100, 2, '.', ''));
            $instdAmt->setAttribute('Ccy', 'EUR');
            $amt->appendChild($instdAmt);

            // Creditor (The Member)
            $cdtr = $doc->createElement('Cdtr');
            $cdtTrfTxInf->appendChild($cdtr);
            $cdtr->appendChild($doc->createElement('Nm', $memberName));

            $cdtrAcct = $doc->createElement('CdtrAcct');
            $cdtTrfTxInf->appendChild($cdtrAcct);
            $id = $doc->createElement('Id');
            $cdtrAcct->appendChild($id);
            $id->appendChild($doc->createElement('IBAN', $memberIban));

            // Remittance Info
            $rmtInf = $doc->createElement('RmtInf');
            $cdtTrfTxInf->appendChild($rmtInf);
            $rmtInf->appendChild($doc->createElement('Ustrd', $tx->getPurpose() ?: 'Zahlung'));
        }

        return $doc->saveXML();
    }
}
