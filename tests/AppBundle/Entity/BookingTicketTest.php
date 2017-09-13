<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\BookingTicket;
use AppBundle\Entity\Ticket;
use PHPUnit\Framework\TestCase;


class BookingTicketTest extends TestCase
{//tests fonvtionnels

    /**
     * @dataProvider ticketProvider
     *
     */
    public function testComputeAmount($birthDate, $discount, $expected)
    {
        $booking = new BookingTicket();
        $booking->setBookingDate(new \DateTime('2020-12-15'));
        $booking->setDayLong(true);
        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime($birthDate));
        $ticket->setDiscount($discount);
        $booking->addTicket($ticket);
        $this->assertEquals($expected, $booking->computeAmount());// test sur un billet journée

        $booking->setDayLong(false);
        $this->assertEquals($expected * .6, $booking->computeAmount()); // test sur un billet demi-journée à 60% du tarif journée
    }

    public function ticketProvider()
    {
        return [
            ['1980-02-01', false, 16],
            ['2017-05-03', false, 0],
            ['1972-06-06',true,10],
            ['1950-02-01',true,12]
        ];

    }
}
