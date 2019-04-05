<?php

namespace App\Service\MicroService;

use XIV\XivService;

class FeedbackMicroService
{
    const SUMMARY_LENGTH = 200;
    
    /**
     * Get a list of private and public feedback
     */
    public static function list($category = false)
    {
        $sdk = new XivService();
        
        $public  = $sdk->Feedback->search();
        $private = $sdk->Feedback->search(['private' => 1]);
        $counts  = [
            'public'  => [],
            'private' => [],
        ];
        
        // Public issues
        foreach ($public as $i => $issue) {
            $counts['public'][$issue->category] = isset($counts['public'][$issue->category]) ? $counts['public'][$issue->category] + 1 : 1;
            
            $summary = explode('.', $issue->message);
            
            // very cheap way to make a summary by adding on sentences
            $issue->summary = $summary[0] .'.';
            $issue->summary = (strlen($issue->summary) < self::SUMMARY_LENGTH && isset($summary[1])) ? $issue->summary .' '. $summary[1] .'.' : $issue->summary;
            $issue->summary = (strlen($issue->summary) < self::SUMMARY_LENGTH && isset($summary[2])) ? $issue->summary .' '. $summary[2] .'.' : $issue->summary;
            $issue->summary = strlen($issue->summary) > self::SUMMARY_LENGTH+50 ? substr($issue->summary, 0, self::SUMMARY_LENGTH+50) .'...' : $issue->summary;
            
            if ($category && $category != $issue->category) {
                unset($public[$i]);
            }
        }
    
        // private issues
        foreach ($private as $i => $issue) {
            $counts['private'][$issue->category] = isset($counts['public'][$issue->category]) ? $counts['public'][$issue->category] + 1 : 1;
    
            // very cheap way to make a summary by adding on sentences
            $issue->summary = $summary[0] .'.';
            $issue->summary = (strlen($issue->summary) < self::SUMMARY_LENGTH && isset($summary[1])) ? $issue->summary .' '. $summary[1] .'.' : $issue->summary;
            $issue->summary = (strlen($issue->summary) < self::SUMMARY_LENGTH && isset($summary[2])) ? $issue->summary .' '. $summary[2] .'.' : $issue->summary;
            $issue->summary = strlen($issue->summary) > self::SUMMARY_LENGTH+50 ? substr($issue->summary, 0, self::SUMMARY_LENGTH+50) .'...' : $issue->summary;
    
            if ($category && $category != $issue->category) {
                unset($private[$i]);
            }
        }
        
        return [
            $public, $private, $counts
        ];
    }
}
