CREATE PROCEDURE [dbo].[spGetRepliesForPost]
	@PostId uniqueidentifier
AS
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where p.ReplyPostId = @PostId