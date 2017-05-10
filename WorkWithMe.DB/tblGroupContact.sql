CREATE TABLE [dbo].[tblGroupContact]
(
	[Id] UNIQUEIDENTIFIER NOT NULL PRIMARY KEY, 
    [OwnerUserId] UNIQUEIDENTIFIER NOT NULL, 
    [TargetUserId] UNIQUEIDENTIFIER NOT NULL, 
    [TargetGroupId] UNIQUEIDENTIFIER NOT NULL, 
    [IsGroupAdmin] BIT NOT NULL, 
    [CanPost] BIT NOT NULL, 
    [CanInvite] BIT NOT NULL, 
    [CanDelete] BIT NOT NULL,
    [InviteStatusId] INT NOT NULL
)
