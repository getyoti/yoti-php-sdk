<?php

namespace Yoti\Test\IDV\Session\Instructions;

use Yoti\IDV\Session\Instructions\Branch\Branch;
use Yoti\IDV\Session\Instructions\ContactProfile;
use Yoti\IDV\Session\Instructions\Document\DocumentProposal;
use Yoti\IDV\Session\Instructions\InstructionsBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Instructions\Instructions
 */
class InstructionsTest extends TestCase
{
    /**
     * @var ContactProfile
     */
    private $contactProfile;

    /**
     * @var DocumentProposal
     */
    private $documentProposal1;

    /**
     * @var DocumentProposal
     */
    private $documentProposal2;

    /**
     * @var Branch
     */
    private $branch;

    public function setup(): void
    {
        $this->contactProfile = $this->createMock(ContactProfile::class);
        $this->documentProposal1 = $this->createMock(DocumentProposal::class);
        $this->documentProposal2 = $this->createMock(DocumentProposal::class);
        $this->branch = $this->createMock(Branch::class);
        parent::setup();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getDocuments
     * @covers ::getContactProfile
     * @covers ::getBranch
     * @covers \Yoti\IDV\Session\Instructions\InstructionsBuilder::build
     * @covers \Yoti\IDV\Session\Instructions\InstructionsBuilder::withDocumentProposal
     * @covers \Yoti\IDV\Session\Instructions\InstructionsBuilder::withContactProfile
     * @covers \Yoti\IDV\Session\Instructions\InstructionsBuilder::withBranch
     */
    public function builderShouldBuildWithAllProperties()
    {
        $result = (new InstructionsBuilder())
            ->withBranch($this->branch)
            ->withContactProfile($this->contactProfile)
            ->withDocumentProposal($this->documentProposal1)
            ->withDocumentProposal($this->documentProposal2)
            ->build();

        $this->assertEquals($this->branch, $result->getBranch());
        $this->assertEquals($this->contactProfile, $result->getContactProfile());
        $this->assertCount(2, $result->getDocuments());
        $this->assertContains($this->documentProposal1, $result->getDocuments());
        $this->assertContains($this->documentProposal2, $result->getDocuments());
    }
}
