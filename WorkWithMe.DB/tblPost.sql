﻿CREATE TABLE [dbo].[tblPost]
(
	[Id] UNIQUEIDENTIFIER NOT NULL PRIMARY KEY, 
    [OwnerUserId] UNIQUEIDENTIFIER NOT NULL, 
    [TargetGroupId] UNIQUEIDENTIFIER NULL, 
    [Title] NVARCHAR(50) NOT NULL, 
    [Content] NVARCHAR(MAX) NOT NULL, 
    [IsSticky] BIT NOT NULL, 
    [IsDeleted] BIT NOT NULL, 
    [TimeStamp] DATETIME NOT NULL, 
    [EventTimeStamp] DATETIME NULL
)
